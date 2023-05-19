<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\AccountsController;

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




Route::post('/books', [BooksController::class , 'index']);
Route::post('/insert_book', [BooksController::class , 'store']);
Route::post('/update_book', [BooksController::class , 'update']);
Route::post('/delete_book', [BooksController::class , 'destroy']);
Route::post('/borrowed_books', [BooksController::class , 'borrowed_books']);
Route::post('/all_borrowed_books', [BooksController::class , 'all_borrowed_books']);
Route::post('/insert_borrowed_book', [BooksController::class , 'insert_borrowed_book']);

Route::post('/insert_to_return_books', [BooksController::class , 'insert_to_return_books']);
Route::post('/get_returned_books', [BooksController::class , 'get_returned_books']);


Route::post('/events', [EventsController::class , 'index']);
Route::post('/insert_event', [EventsController::class , 'store']);
Route::post('/update_event', [EventsController::class , 'update']);
Route::post('/delete_event', [EventsController::class , 'destroy']);


Route::post('/accounts', [AccountsController::class , 'index']);
Route::post('/insert_user', [AccountsController::class , 'store']);
Route::post('/update_user', [AccountsController::class , 'update']);
Route::post('/delete_user', [AccountsController::class , 'destroy']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
