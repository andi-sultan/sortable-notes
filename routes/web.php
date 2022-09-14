<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\NoteLabelController;
use App\Models\NoteLabel;

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

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard', ['title' => 'Dashboard']);
});

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
