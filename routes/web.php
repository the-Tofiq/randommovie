<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/set-hook', [\App\Http\Controllers\MovieController::class,'tmSetTHook']);
/*Route::get('/hook', function (){
    $return =  \Illuminate\Support\Facades\Http::post( 'https://api.telegram.org/bot'.config('api_keys.tm_api_key').'/getWebhookInfo');
    dd(json_decode($return->body()));
});*/

Route::post('/tm-answer', [\App\Http\Controllers\MovieController::class,'tmBotAnswers'])->name('tmBotAnswers');
Route::get('/form',[\App\Http\Controllers\MovieController::class,'forTest']);

