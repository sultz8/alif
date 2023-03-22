<?php

use App\Http\Controllers\API\Auth\LoginAPIController;
use App\Http\Controllers\API\Auth\RegisterAPIController;
use App\Http\Controllers\API\ContactAPIController;
use App\Http\Controllers\API\EmailAPIController;
use App\Http\Controllers\API\PhoneAPIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'guest:api'], function () {
    // Auth
    Route::post('/login', [LoginAPIController::class, 'login']);
    Route::post('/register', [RegisterAPIController::class, 'register']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [LoginAPIController::class, 'logout']);

    // Контакты
    Route::apiResource('/contacts', ContactAPIController::class);

    // Email адреса
    Route::post('/contacts/{contact}/emails', [EmailAPIController::class, 'store']);
    Route::put('/contacts/{contact}/emails', [EmailAPIController::class, 'update']);
    Route::get('contacts/{contact}/emails', [EmailAPIController::class, 'index']);
    Route::delete('/contacts/emails/{email}', [EmailAPIController::class, 'destroy']);

    // Телефонные номера
    Route::post('/contacts/{contact}/phones', [PhoneAPIController::class, 'store']);
    Route::put('/contacts/{contact}/phones', [PhoneAPIController::class, 'update']);
    Route::get('contacts/{contact}/phones', [PhoneAPIController::class, 'index']);
    Route::delete('/contacts/phones/{phone}', [PhoneAPIController::class, 'destroy']);
});
