<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController};

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
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('send-password-link',[AuthController::class,'sendForgetPassword']);
Route::post('verify-forgot-password-mail',[AuthController::class, 'verifyForgotPasswordOTP']);
Route::post('change-forgot-password',[AuthController::class, 'changeForgetPassword']);

Route::middleware('auth:api')->group(function(){
    Route::post('logout',[AuthController::class,'logout']);
});
Route::get('/offer/{id}', function ($id) {
    $offer = \App\Models\Offer::with('store')->findOrFail($id);
    return response()->json([
        'offer' => $offer,
        'store' => $offer->store,
    ]);
});
