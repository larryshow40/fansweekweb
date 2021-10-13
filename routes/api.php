<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::post('code', 'ApiController@storeCode');
Route::post('like-code', 'ApiController@likeCode');
Route::post('dislike-code', 'ApiController@dislikeCode');
Route::post('store-comment', 'ApiController@storeComment');
Route::get('list-codes', 'ApiController@listCodes');
Route::post('paystack/webhook', 'ApiController@paystackWebhook');
Route::get('paystack/callback', 'ApiController@paystackCallback');

