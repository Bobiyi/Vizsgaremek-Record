<?php

use App\Http\Controllers\RecordController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;

//onilne DOCS
Route::redirect('/','/api/documentation');

//About
Route::get('/about',function () {
    return response()->json([
        'appName'=>env('APP_NAME'),
        'authors'=>['frontend'=>'Kiss Erik Adolf','backend'=>'Halmai Bence'],
        'gitRepository'=>'https://github.com/Bobiyi/Record_Vizsga',
        'version'=>'1.0.0',
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
        'timestamp' => now()->format('Y-m-d, H:i:s')
    ]);
});

//Available publicly
Route::post('/user/register',[UserController::class,'register']);
Route::post('/user/login',[UserController::class,'login']);

Route::get('/records',[RecordController::class,'getRecords']);
Route::redirect('/record','/api/records',301);
Route::get('/records/types',[RecordController::class,'getTypes']);
Route::get('/records/types/{typeName}',[RecordController::class,'getRecordsByType']);
Route::get('/records/{recordId}',[RecordController::class,'getRecord']);

Route::get('/artists',[RecordController::class,'getArtists']);
Route::redirect('/artist','/api/artists',301);
Route::get('/artists/{artistId}',[RecordController::class,'getArtist']);

Route::get('/records-artist/{recordId}',[RecordController::class,'getRecordsArtists']);
Route::get('/artists-record/{artistId}',[RecordController::class,'getArtistsRecords']);

Route::get('/favourite/{userId}',[UserController::class,'getFavouritesById']);
Route::get('/favourite/{userId}/list',[UserController::class,'getFavouritesListById']);

//Requires Login
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/logout',[UserController::class,'logout']);
    Route::delete('/user/delete/{user}',[UserController::class,'deleteAccount']);
    Route::post('/favourite',[UserController::class,'favourite']);
    Route::post('/queue-request',[RequestController::class,'addRequest']);
    Route::get('/requests/user/{userId}',[RequestController::class,'getRequestsWithUserId']);
});

//Admin only
Route::middleware(['auth:sanctum',AdminOnly::class])->group(function () {
    Route::get('/requests/{requestId}',[RequestController::class,'getRequestsById']);
    Route::get('/requests',[RequestController::class,'getRequests']);
    Route::get('/users',[UserController::class,'getUsers']);
    Route::post('/record',[RecordController::class,'addRecord']);
    Route::post('/artist',[RecordController::class,'addArtist']);
    Route::post('/link-artist-record',[RecordController::class,'linkArtistRecord']);

    Route::put('/records/{recordId}',[RecordController::class,'updateRecord']);
    Route::put('/artists/{artistId}',[RecordController::class,'updateArtist']);

    Route::delete('/records/{recordId}',[RecordController::class,'deleteRecord']);
    Route::delete('/artists/{artistId}',[RecordController::class,'deleteArtist']);

    Route::patch('/request/{requestId}/accept',[RequestController::class,'acceptRequest']);
    Route::patch('/request/{requestId}/reject',[RequestController::class,'rejectRequest']);
});
