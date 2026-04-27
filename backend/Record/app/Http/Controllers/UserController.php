<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StoreFavouriteRequest;
use App\Http\Resources\RecordResource;
use App\Http\Resources\UserResource;
use App\Models\Favourite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    
    /**
     * Checks if the recieved name and password matches a database record with the same name and hashed password.
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request) {
        $data = $request->toModel();

            $user = User::where('name', $data['name'])->first();

            if($user != null) {
                if(Hash::check($data['password'],$user->getAuthPassword())) {
                    // delete all prevoius tokens
                    $user->tokens()->delete();

                    // create token
                    $token = $user->createToken('api-token', ['*'],now()->addDays(3))->plainTextToken;

                    //update updated_at filed to now
                    $user->updated_at=now();

                    return response()->json(['message'=>'User Logged in!','token' => $token, 'user' => UserResource::make($user)],200);
                } else {
                    return response()->json(['message'=>'Password or username not correct!'],403);
                }

            } else{
                return response()->json(['message'=>'User Not Found!'],404);
            }

    }

    /**
     * Adds a new User to the databases User table (does not generate / return API token)
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request) {

        $data = $request->toModel();

        $user = User::create([
            'password_hash'=>Hash::make($data['password']),
            'name'=>$data['name'],
            'email'=>$data['email'],
            'phone'=>$data['phone']
        ]);

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => UserResource::make($user)
        ], 201);
    }

    /**
     * Deletes the Users API Token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        $token = $request->bearerToken();

        if(!$token) {
            return response()->json(['message' => 'No token provided'], 401);
        }
        
        $accessToken=PersonalAccessToken::findToken($token);

        if(!$accessToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $accessToken->delete();

        return response()->json(['message' => 'User Logged out!'], 205);
    }

    /**
     * Completley removes the Users Account from the database
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(User $user) {

        $this->authorize('delete',$user);

        $user->delete();

        return response()->noContent();
    }

    /**
     * Get all Users
     * @return JsonResponse
     */
    public function getUsers() {
        $users = User::all();

        $usersRes = UserResource::collection($users);

        return response()->json($usersRes);
    }

        /**
     * Inserts the corresponding user id and record id into the favourite table
     * if the keys already exists in the table it removes the (un favouriting)
     * @param StoreFavouriteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favourite(StoreFavouriteRequest $request) {
        $data=$request->toModel();

        $user = $request->user();


            $recordId = $data['record_id'];
            $alreadyFavourited= Favourite::where('user_id',$user->id)->where('record_id',$data['record_id'])->exists();
            if(!$alreadyFavourited) {
                Favourite::create(['user_id'=>$user->id,'record_id'=>$data['record_id']]);

                return response()->json(['message'=>"{$user->name} favourited Record(ID) {$recordId}"],201);
            } else {
                Favourite::where('user_id',$user->id)->where('record_id',$data['record_id'])->delete();

                return response()->json(['message'=>"{$user->name} un favourited Record(ID) {$recordId}"],200);
            }
    }

    /**
     * Get users favourites (return ids)
     * @param int $userId
     * @return JsonResponse
     */
    public function getFavouritesById(int $userId) {
        $list = Favourite::where('user_id',$userId)->get()->map(function($favourite) {
            return[
                'recordId'=>$favourite->record_id
            ];
        });

        return response()->json($list);
    }

    /**
     * Get users favourites (return list)
     * @param int $userId
     * @return JsonResponse
     */
    public function getFavouritesListById(int $userId) {
        $list = User::with('favourites')->findorfail($userId);

        return response()->json(RecordResource::collection($list->favourites));
    }
    
}
