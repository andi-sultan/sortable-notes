<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\NoteLabelController;
use App\Http\Controllers\UserProfileController;

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

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

// Route::get('/signup', [RegisterController::class, 'index'])->middleware('guest');
// Route::post('/register', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::post('/notes/getNotes', [NoteController::class, 'getNotes']);
    Route::post('/notes/set-label', [NoteController::class, 'setLabel']);
    Route::resource('/notes', NoteController::class);

    Route::post('/labels/getLabels', [LabelController::class, 'getLabels']);
    Route::get('/labels/get', [LabelController::class, 'getAll']);
    Route::resource('/labels', LabelController::class);

    Route::get('/notes-by-label/{id}', [NoteLabelController::class, 'viewNotesByLabel']);
    Route::post('/note-labels/get-data', [NoteLabelController::class, 'getData']);
    Route::post('/note-labels/save-positions', [NoteLabelController::class, 'savePositions']);
    Route::resource('/note-labels', NoteLabelController::class);

    Route::get('/user-profile', [UserProfileController::class, 'index']);
    Route::post('/user-profile/update-profile', [UserProfileController::class, 'updateData']);
    Route::post('/user-profile/update-password', [UserProfileController::class, 'saveNewPassword']);
});
