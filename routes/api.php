<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\KabanCardController;
use App\Http\Controllers\KabanColumnController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

## Tokens
Route::get('access_token', [AccessTokenController::class, 'create']);
Route::get('access_token/check', [AccessTokenController::class, 'checkToken']);

## Columns
Route::get('columns', [KabanColumnController::class, 'index']);
Route::post('column', [KabanColumnController::class, 'create']);
Route::delete('column/{columnId}', [KabanColumnController::class, 'destroy']);

## Cards
Route::get('list-cards', [KabanCardController::class, 'index']);
Route::post('card', [KabanCardController::class, 'create']);
Route::patch('card/{card}', [KabanCardController::class, 'update']);
Route::delete('card/{card}', [KabanCardController::class, 'destroy']);
