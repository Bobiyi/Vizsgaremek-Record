<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\StoreQueueRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\Artist;
use App\Models\Record;
use App\Models\RecordType;
use App\Models\RequestQueue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    /**
     * Ads a new entry to the Queue(requests Table) 
     * @param StoreQueueRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRequest(StoreQueueRequest $request)
    {
        //get User based on provided token
        $user = $request->user();

        $data = $request->toModel();
        $data['user_id'] = $user->id;

        // merge file paths into payload
        $payload = json_decode($data['payload'], true);

        switch($data['type']) {
            case 'new_record': 
                if( ($payload['releaseYear']?? null)>date('Y')) return response()->json(['message'=>'releaseYear cannot be in the future!'],422);
                break;
            case 'edit_record': 
                if( ($payload['releaseYear']?? null)>date('Y')) return response()->json(['message'=>'releaseYear cannot be in the future!'],422);
                break;
            
            case 'new_artist':
                if( ($payload['activeSince']?? null)>date('Y')) return response()->json(['message'=>'activeSince cannot be in the future!'],422);
                break;
            case 'edit_artist':
                if( ($payload['activeSince']?? null)>date('Y')) return response()->json(['message'=>'activeSince cannot be in the future!'],422);
                break;
        }
        //rename img fileds to mactch database fields
        foreach (['artistIcon' => 'icon_path', 'artistCover' => 'cover_path', 'recordFile' => 'file_path'] as $fileField => $payloadKey) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);

                $fileName = $file->getClientOriginalName();

                $path = Storage::disk('Queue')->putFileAs('',$file,$fileName);
                $payload[$payloadKey] = $path;
            }
        }

        $data['payload'] = $payload;

        $created = RequestQueue::create($data);

        return response()->json(['message' => 'Request created!', 'id' => $created->id], 201);
    }

    /**
     * Updates the reuests and inserts its payload into the table
     * @param int $id
     * @param UpdateRequestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptRequest(int $id,UpdateRequestRequest $request) {
        $requestToModify = RequestQueue::findOrFail($id);

        if($requestToModify->status!=='pending') return response()->json(['message'=>"Request has already been $requestToModify->status."],409);

        $data=$request->toModel();

        $payload =$requestToModify->payload;

        $dataToInsert = [
            'status'=>'accepted',
            'admin_note'=>$data['admin_note'],
            'reviewed_at'=>now()
        ];

        switch ($requestToModify->type) {
            case 'new_record':
                 // map payload keys to database columns before creating
                $recordData = [
                    'name'         => $payload['recordName'],
                    'type_id'      => $payload['typeId'],
                    'release_year' => $payload['releaseYear'] ?? null,
                    'length'       => $payload['length'] ?? null,
                    'file_path'    => null
                ];


                $inserted = Record::create($recordData); // firstly create the REcord with no imgs
                $insertedName = $inserted->name;
                $insertedId = $inserted->id;

                if (isset($payload['file_path'])) {
                    $extension = pathinfo($payload['file_path'], PATHINFO_EXTENSION);
                    $newPath = $insertedName . '.' . $extension;
                    Storage::disk('Records')->put($newPath, Storage::disk('Queue')->get($payload['file_path']));
                    Storage::disk('Queue')->delete($payload['file_path']);
                    $inserted->update(['file_path' => 'Records/'.$newPath]); // then update it with its img (if it has)
                    
                }

                break;

            case 'new_artist':
                $artistData = [
                    'name'         => $payload['artistName'],
                    'active_since' => $payload['activeSince'] ?? null,
                    'nationality'  => $payload['artistNationality'] ?? null,
                    'url'          => $payload['artistWebsite'] ?? null,
                    'is_group'     => $payload['isGroup'],
                ];

                $inserted = Artist::create($artistData);
                $insertedName = $inserted->name;
                $insertedId = $inserted->id;

                if (isset($payload['icon_path'])) {
                    $extension = pathinfo($payload['icon_path'], PATHINFO_EXTENSION);
                    $newPath = $insertedName . 'Icon.' . $extension;
                    Storage::disk('Artists')->put($newPath, Storage::disk('Queue')->get($payload['icon_path']));
                    Storage::disk('Queue')->delete($payload['icon_path']);
                    $inserted->update(['icon_path' => 'Artists/'.$newPath]);
                }

                if (isset($payload['cover_path'])) {
                    $extension = pathinfo($payload['cover_path'], PATHINFO_EXTENSION);
                    $newPath = $insertedName . 'Banner.' . $extension;
                    Storage::disk('Artists')->put($newPath, Storage::disk('Queue')->get($payload['cover_path']));
                    Storage::disk('Queue')->delete($payload['cover_path']);
                    $inserted->update(['cover_path' => 'Artists/'.$newPath]);
                }

                break;
                
                case 'edit_record':
                    $recordData = [
                        'id'           => $payload['recordToModifyId'],
                        'name'         => $payload['recordName'],
                        'type_id'      => $payload['typeId'],
                        'release_year' => $payload['releaseYear'] ?? null,
                        'length'       => $payload['length'] ?? null,
                    ];
                    
                    $recordToModify = Record::findOrFail($recordData['id']);
                    $modifiedName = $recordToModify->name;
                    $modifiedId = $recordToModify->id;

                    if (isset($payload['file_path'])) {
                        $extension = pathinfo($payload['file_path'], PATHINFO_EXTENSION); // fix: use file_path
                        $newPath = $modifiedName . '.' . $extension;

                        Storage::disk('Records')->put($newPath, Storage::disk('Queue')->get($payload['file_path'])); // fix: use file_path

                        if ($recordToModify->file_path) {
                            Storage::disk('Records')->delete(basename($recordToModify->file_path));
                        }

                        Storage::disk('Queue')->delete($payload['file_path']); // fix: use file_path
                        $recordData['file_path'] = 'Records/' . $newPath; // fix: add prefix
                    }

                    $recordToModify->update(Arr::except($recordData, ['id']));
                    break;
                    
                case 'edit_artist':
                    $artistData = [
                        'id'           => $payload['artistToModifyId'],
                        'name'         => $payload['artistName'],
                        'active_since' => $payload['activeSince'] ?? null,
                        'nationality'  => $payload['artistNationality'] ?? null,
                        'url'          => $payload['artistWebsite'] ?? null,
                        'is_group'     => $payload['isGroup'],
                    ];
                    
                    $artistToModify = Artist::findOrFail($artistData['id']);
                    $modifiedName = $artistToModify->name;
                    $modifiedId = $artistToModify->id;

                    if (isset($payload['icon_path'])) {
                        $extension = pathinfo($payload['icon_path'], PATHINFO_EXTENSION); // fix: use icon_path
                        $newPath = $modifiedName . 'Icon.' . $extension;

                        Storage::disk('Artists')->put($newPath, Storage::disk('Queue')->get($payload['icon_path'])); // fix: use icon_path

                        if ($artistToModify->icon_path) {
                            Storage::disk('Artists')->delete(basename($artistToModify->icon_path));
                        }

                        Storage::disk('Queue')->delete($payload['icon_path']); // fix: use icon_path
                        $artistData['icon_path'] = 'Artists/' . $newPath; // fix: add prefix
                    }

                    if (isset($payload['cover_path'])) {
                        $extension = pathinfo($payload['cover_path'], PATHINFO_EXTENSION); // fix: use cover_path
                        $newPath = $modifiedName . 'Banner.' . $extension;

                        Storage::disk('Artists')->put($newPath, Storage::disk('Queue')->get($payload['cover_path'])); // fix: use cover_path

                        if ($artistToModify->cover_path) {
                            Storage::disk('Artists')->delete(basename($artistToModify->cover_path));
                        }

                        Storage::disk('Queue')->delete($payload['cover_path']); // fix: use cover_path
                        $artistData['cover_path'] = 'Artists/' . $newPath; // fix: add prefix
                    }

                    $artistToModify->update(Arr::except($artistData, ['id']));
                    break;
            default:
                return response()->json(['message' => 'Unknown request type.'], 422);
        }

        $requestToModify->update($dataToInsert);

        if(str_contains($requestToModify->type,'new')) 
            return response()->json(['message' => 'Request accepted!','modified' => "$insertedName has been added to the database!",'insertedId'=>$insertedId], 200);

        return response()->json(['message' => 'Request accepted!','modified' => "$modifiedName has been modified!",'modifiedId'=>$modifiedId], 200);
    }

    /**
     * Rejects a request, it wont return this request anymore
     * @param int $id
     * @param UpdateRequestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectRequest(int $id,UpdateRequestRequest $request) {
        $requestToModify = RequestQueue::findOrFail($id);

        if($requestToModify->status!=='pending') return response()->json(['message'=>"Request has already been $requestToModify->status."],409);

        $data=$request->toModel();

        $dataToInsert = [
            'status'=>'rejected',
            'admin_note'=>$data['admin_note'],
            'reviewed_at'=>now()
        ];

        $requestToModify->update($dataToInsert);

        return response()->json(['message' => 'Request Rejected!'], 200);
    }

    /**
     * Returns all pending requests
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequests(Request $request) {
        $requests = RequestQueue::with('user')
            ->where('status', 'pending')
            ->get()
            ->map(fn (RequestQueue $item) => $this->formatRequestToJsonFields($item));

        return response()->json($requests);
    }

    /**
     * Returns one request based on id
     * @param Request $request
     * @param int $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestsById(Request $request,int $requestId) {
        $request = RequestQueue::with('user')
            ->where('status', 'pending')
            ->where('id', $requestId)
            ->get()
            ->map(fn (RequestQueue $item) => $this->formatRequestToJsonFields($item));

        if(!$request) return response()->json(['message'=>'Request Not Found!'],404);
        return response()->json($request[0]);
    }

    /**
     * Return(s) request(s) based on userId
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestsWithUserId(int $userId) {
        $targetUser = User::findOrFail($userId);
        
        $this->authorize('viewRequests',$targetUser);

        $request = RequestQueue::with('user')
            ->where('status', 'pending')
            ->where('user_id', $userId)
            ->get()
            ->map(fn (RequestQueue $item) => $this->formatRequestToJsonFields($item));

        if(!$request) return response()->json(['message'=>'Request(s) Not Found!'],404);
        return response()->json($request);
    }


    /**
     * Formats the request to a JSON structure
     * @param RequestQueue $item
     * @return array{createdAt: string, data: array|array{activeSince: mixed, artistName: mixed, artistNationality: mixed, artistToModifyId: mixed, artistWebsite: mixed, coverUrl: string|null, iconUrl: string|null, isGroup: mixed|array{activeSince: mixed, artistName: mixed, artistNationality: mixed, artistWebsite: mixed, coverUrl: string|null, iconUrl: string|null, isGroup: mixed}|array{coverUrl: string|null, length: mixed, recordName: mixed, recordToModifyId: mixed, releaseYear: mixed, type: mixed}|array{coverUrl: string|null, length: mixed, recordName: mixed, releaseYear: mixed, type: mixed}, id: mixed, type: mixed, userId: mixed, userName: string}}
     */
    private function formatRequestToJsonFields(RequestQueue $item): array
    {
        $payload = $item->payload;

        $data = match ($item->type) {
            'new_record' => [
                'recordName'  => $payload['recordName'],
                'type'        => RecordType::find($payload['typeId'])?->type_name,
                'releaseYear' => $payload['releaseYear'] ?? null,
                'length'      => $payload['length'] ?? null,
                'coverUrl'    => isset($payload['file_path']) ? asset('storage/Queue/' . $payload['file_path']) : null,
            ],
            'edit_record' => [
                'recordToModifyId' => $payload['recordToModifyId'],
                'recordName'       => $payload['recordName'],
                'type'             => RecordType::find($payload['typeId'])?->type_name,
                'releaseYear'      => $payload['releaseYear'] ?? null,
                'length'           => $payload['length'] ?? null,
                'coverUrl'         => isset($payload['file_path']) ? asset('storage/Queue/' . $payload['file_path']) : null,
            ],
            'new_artist' => [
                'artistName'        => $payload['artistName'],
                'activeSince'       => $payload['activeSince'] ?? null,
                'artistNationality' => $payload['artistNationality'] ?? null,
                'artistWebsite'     => $payload['artistWebsite'] ?? null,
                'isGroup'           => $payload['isGroup'],
                'iconUrl'           => isset($payload['icon_path']) ? asset('storage/Queue/' . $payload['icon_path']) : null,
                'coverUrl'          => isset($payload['cover_path']) ? asset('storage/Queue/' . $payload['cover_path']) : null,
            ],
            'edit_artist' => [
                'artistToModifyId'  => $payload['artistToModifyId'],
                'artistName'        => $payload['artistName'],
                'activeSince'       => $payload['activeSince'] ?? null,
                'artistNationality' => $payload['artistNationality'] ?? null,
                'artistWebsite'     => $payload['artistWebsite'] ?? null,
                'isGroup'           => $payload['isGroup'],
                'iconUrl'           => isset($payload['icon_path']) ? asset('storage/Queue/' . $payload['icon_path']) : null,
                'coverUrl'          => isset($payload['cover_path']) ? asset('storage/Queue/' . $payload['cover_path']) : null,
            ],
            default => $payload,
        };

        return [
            'id'        => $item->id,
            'userId'    => $item->user_id,
            'userName'  => $item->user->name,
            'type'      => $item->type,
            'data'      => $data,
            'createdAt' => $item->created_at->format('Y-M-d h:i:s'),
        ];
    }
}
