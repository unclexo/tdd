<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaUploaderController;
use App\Http\Controllers\PostController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('posts')->controller(PostController::class)->group(function() {
        Route::get('/', 'index')->name('posts.index');

        Route::get('/create', 'create')->name('posts.create');

        Route::get('/{post}', 'show')->name('posts.show');

        Route::post('/store', 'store')->name('posts.store');

        Route::get('/{post}/edit', 'edit')->name('posts.edit');

        Route::patch('/{post}', 'update')->name('posts.update');

        Route::delete('/{post}', 'destroy')->name('posts.delete');
    });

    Route::prefix('media')->controller(MediaUploaderController::class)->group(function() {
        Route::post('/upload', 'upload')->name('upload.common');

        Route::post('/upload/renamed', 'rename')->name('upload.renamed');

        Route::post('/upload/validation', 'validateUpload')->name('upload.validation');
    });
});
