<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkArtistRecordRequest;
use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateArtistRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\RecordResource;
use App\Http\Resources\RecordTypeResource;
use App\Models\Artist;
use App\Models\ArtistRecord;
use App\Models\Record;
use App\Models\RecordType;
use Illuminate\Support\Facades\Storage;

class RecordController extends Controller
{

    /**
     * Returns all Records
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecords()
    {
        $list = Record::with('artists')->get();
        $listRes = RecordResource::collection($list);

        return response()->json($listRes);
    }


    /**
     * Returns the Record on a given id.
     * @param int $recordId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecord(int $recordId) {
        $record = Record::with('artists')->findOrFail($recordId);

        $recordRes = RecordResource::make($record);

        return response()->json($recordRes);
    }

    /**
     * Return all Records.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtists() {
        $list = Artist::all();

        $listRes = ArtistResource::collection($list);

        return response()->json($listRes);
    }

    /**
     * Return the Artist on a given Id.
     * @param int $artistId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtist(int $artistId) {
        $artist = Artist::findOrFail($artistId);

        $artistRes = ArtistResource::make($artist);

        return response()->json($artistRes);
    }

    /**
     * Returns all types
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTypes() {
        $types = RecordType::all();

        $typesRes = RecordTypeResource::collection($types);

        return response()->json($typesRes);
    }

    /**
     * Returns all records with the given type in the parameter.
     * @param string $typeName
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecordsByType(string $typeName) {
        $records = Record::with('type','artists')->whereHas('type', 
            function ($query) use($typeName) 
            {
                $query->where('type_name',$typeName);
            })->get();

        $recordsRes = RecordResource::collection($records);

        return response()->json($recordsRes);
    }

    /**
     * Return a given Records All Artists
     * @param int $recordId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getRecordsArtists(int $recordId) {
        $record = Record::with('artists')->findOrFail($recordId);

        return response()->json(ArtistResource::collection($record->artists));
    }

    /**
     * Return a given Artists All Records
     * @param int $artistId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistsRecords(int $artistId) {
        $artist = Artist::with('records')->findOrFail($artistId);

        return response()->json(RecordResource::collection($artist->records));
    }

    /**
     * Adds a new Record to the database
     * @param StoreRecordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRecord(StoreRecordRequest $request) {
        $data = $request->toModel();

        $path = null;
        
        if($data['release_year']>date('Y')) return response()->json(['message'=>'releaseYear cannot be in the future!'],422);

        if($request->hasFile('recordFile')) {

            $file = $request->file('recordFile');

            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            $extension = $file->getClientOriginalExtension();

            $savedFileName = $fileName.'_'.uniqid().'.'.$extension;

            $path = $file->storeAs('Records',$savedFileName,'public');
        }
        
        $record=Record::create([
            'name' => $data['name'],
            'type_id' => $data['type_id'],
            'release_year' => $data['release_year'],
            'length' => $data['length'],
            'file_path' => $path
        ]);

        return response()->json(['message'=>'Record created!','id'=>$record->id],201);
    }

    /**
     * Adds a new Artist to the database
     * @param StoreArtistRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArtist(StoreArtistRequest $request) {
        $data = $request->toModel();

        if($data['active_since']>date('Y')) return response()->json(['message'=>'active Since cannot be in the future!'],422);

        if($request->hasFile('artistIcon')) {
            $picturefile = $request->file('artistIcon');

            $pictureFileName = pathinfo($picturefile->getClientOriginalName(),PATHINFO_FILENAME);

            $pictureExtension = $picturefile->getClientOriginalExtension();

            $savedPictureFileName = $pictureFileName.'Icon_'.uniqid().'.'.$pictureExtension;

            $picturefile->storeAs('Artists',$savedPictureFileName,'public');
        }

        if($request->hasFile('artistCover')) {
            $coverfile = $request->file('artistCover');

            $coverFileName = pathinfo($coverfile->getClientOriginalName(),PATHINFO_FILENAME);

            $coverExtension = $coverfile->getClientOriginalExtension();

            $savedCoverFileName = $coverFileName.'Banner_'.uniqid().'.'.$coverExtension;

            $coverfile->storeAs('Artists',$savedCoverFileName,'public');
        }


        $data['icon_path']=isset($savedPictureFileName) ? 'Artists/'.$savedPictureFileName:null;
        $data['cover_path']=isset($savedCoverFileName) ? 'Artists/'.$savedCoverFileName:null;


        $artist = Artist::create($data);

        return response()->json(['message'=>'Artist created!','id'=>$artist->id]);
    }
        /**
         * Updates a Record in the database
         * @param UpdateRecordRequest $request
         * @param int $recordId
         * @return \Illuminate\Http\JsonResponse
         */
    public function updateRecord(UpdateRecordRequest $request,int $recordId) {
       $recordToModify = Record::findOrFail($recordId);
        $oldFilePath = $recordToModify->file_path;

        $data= $request->toModel();

        if($request->hasFile('recordFile')) {
            if ($oldFilePath) {
                // file_path is stored as Records/<filename> in the public disk
                Storage::disk('public')->delete($oldFilePath);
             }

            $file = $request->file('recordFile');

            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            $extension = $file->getClientOriginalExtension();

            $savedFileName = $fileName.'_'.uniqid().'.'.$extension;

            $file->storeAs('Records', $savedFileName, 'public');
            $data['file_path'] = 'Records/' . $savedFileName;

        }

        $updated = $recordToModify->update($data);

        if($updated) return response()->json(['message'=>'Record updated!'],200);
        return response()->json(['message' => 'Failed to update record.'], 500);
    }


        /**
         * Updates an Artist in the database
         * @param UpdateArtistRequest $request
         * @param int $artistId
         * @return \Illuminate\Http\JsonResponse
         */
    public function updateArtist(UpdateArtistRequest $request, int $artistId) {
        $artistToModify = Artist::findOrFail($artistId);
        $oldFilePathIcon = $artistToModify->icon_path;
        $oldFilePathCover = $artistToModify->cover_path;

        $data= $request->toModel();

        unset($data['icon_path'], $data['cover_path']);
        if($request->hasFile('artistIcon')) {
            if ($oldFilePathIcon) {
                Storage::disk('public')->delete($oldFilePathIcon);
            }

            $file = $request->file('artistIcon');

            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            $extension = $file->getClientOriginalExtension();

            $savedFileName = $fileName.'Icon_'.uniqid().'.'.$extension;

            $file->storeAs('Artists', $savedFileName, 'public');
            $data['icon_path'] = 'Artists/' . $savedFileName;

        }

        if($request->hasFile('artistCover')) {
            if ($oldFilePathCover) {
                Storage::disk('public')->delete($oldFilePathCover);
            }

            $file = $request->file('artistCover');

            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

            $extension = $file->getClientOriginalExtension();

            $savedFileName = $fileName.'Banner_'.uniqid().'.'.$extension;

            $file->storeAs('Artists', $savedFileName, 'public');
            $data['cover_path'] = 'Artists/' . $savedFileName;

        }

        $updated = $artistToModify->update($data);

        if($updated) return response()->json(['message'=>'Artist updated!'],200);
        return response()->json(['message' => 'Failed to update Artist.'], 500);
    }

    /**
     * Deletes the Record on a given id
     * @param int $recordId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function deleteRecord(int $recordId) {
        $record = Record::findOrFail($recordId);

        if($record->file_path &&!(Storage::disk('public')->delete($record->file_path))) {
            return response()->json(['message'=>'Failed to delete Records Cover!'],500);
        } 

        $deleted = $record->delete();

        if($deleted) return response()->noContent();
        return response()->json(['message'=>'Failed to delete Record!'],500);
    }

    /**
     * Deletes the Artist on a given id
     * @param int $artistId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function deleteArtist(int $artistId) {
        $artist = Artist::findOrFail($artistId);

        if($artist->icon_path && !(Storage::disk('public')->delete($artist->icon_path))) 
            return response()->json(['message'=>'Failed to delete Artists Icon!'],500);

        if($artist->cover_path &&!(Storage::disk('public')->delete($artist->cover_path)))
             return response()->json(['message'=>'Failed to delete Artists Banner!'],500);

        $deleted = $artist->delete();

        if($deleted) return response()->noContent();
        return response()->json(['message'=>'Failed to delete artist!'],500);
    }



    /**
     * Links an Artist to a Record (inserts into the Record_Artist Junction table)
     * @param LinkArtistRecordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkArtistRecord(LinkArtistRecordRequest $request) {
        $data = $request->toModel();

        $selectedArtist = Artist::find($data['artist_id']);
        $selectedRecord = Record::find($data['record_id']);
        try {
            ArtistRecord::create($data);
        
            return response()->json(['message'=>"{$selectedArtist->name} linked to {$selectedRecord->name}!"],201);

        } catch(\Exception $e) {

            return response()->json(['message'=>'Linking failed!'],500);
        }
    }
}
