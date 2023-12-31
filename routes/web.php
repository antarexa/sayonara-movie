<?php

use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\MovieAttachController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TvShowController;
use App\Http\Controllers\Frontend\WelcomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/movies', [FrontendMovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie:slug}', [FrontendMovieController::class, 'show'])->name('movies.show');
Route::get('/tv-shows', [FrontendTvShowController::class, 'index'])->name('tvShows.index');
Route::get('/tv-shows/{tv_show:slug}', [FrontendTvShowController::class, 'show'])->name('tvShows.show');
Route::get('/tv-shows/{tv_show:slug}/seasons/{season:slug}', [FrontendTvShowController::class, 'seasonShow'])->name('season.show');
Route::get('/episodes/{episode:slug}', [FrontendTvShowController::class, 'showEpisode'])->name('episodes.show');
Route::get('/casts', [FrontendCastController::class, 'index'])->name('casts.index');

Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Index');
    })->name('index');
    Route::resource('/movies', MovieController::class);
    Route::get(
        '/movies/{movie}/attach',
        [MovieAttachController::class, 'index']
    )->name('movies.attach');

    Route::delete('/trailer-urls/{trailer_url}', [MovieAttachController::class, 'destroyTrailer'])->name('trailers.destroy');
    Route::delete('/downloads/{download}', [MovieAttachController::class, 'destroyDownload'])->name('downloads.destroy');
    Route::resource('/tv-shows', TvShowController::class);
    Route::resource('/tv-shows/{tv_show}/seasons', SeasonController::class);
    Route::resource('/tv-shows/{tv_show}/seasons/{season}/episodes', EpisodeController::class);

});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
