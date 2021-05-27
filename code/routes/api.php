<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\TestController;

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

// Movies Route
// Route::post('/create-movie', [TestController::class, 'addMovie']);
// Route::get('/movies', [TestController::class, 'movies']);
// Route::get('/single-movie', [TestController::class, 'singleMovie']);
// Route::put('/update-movie', [TestController::class, 'updateMovie']);
// Route::delete('/delete-movie', [TestController::class, 'deleteMovie']);
Route::post('/create-movie', [MovieController::class, 'createMovie']);
Route::get('/movies', [MovieController::class, 'showMovies']);
Route::get('/single-movie', [MovieController::class, 'singleMovie']);
Route::put('/update-movie', [MovieController::class, 'updateMovie']);
Route::delete('/delete-movie', [MovieController::class, 'deleteMovie']);

//Users Route
Route::post('/create-user', [UserController::class, 'createUser']);
Route::get('/users', [UserController::class, 'showUsers']);
Route::get('/single-user', [UserController::class, 'singleUser']);
Route::put('/update-user', [UserController::class, 'updateUser']);
Route::delete('/delete-user', [UserController::class, 'deleteUser']);

//Rentals Route
Route::post('/create-rental', [RentalController::class, 'createRental']);
Route::get('/rentals', [RentalController::class, 'showRentals']);
Route::get('/single-rental', [RentalController::class, 'singleRental']);
Route::put('/update-rental', [RentalController::class, 'updateRental']);
Route::delete('delete-rental', [RentalController::class, 'deleteRental']);