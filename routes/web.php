<?php

use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThoeryOfChangeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
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
    return redirect('/login');
});



Auth::routes();
// In routes/web.php
Route::get('/register', function () {
    return redirect('/'); // Redirect to home or any other route
});
Route::post('/password/update', [ResetPasswordController::class, 'update'])->name('user.update.password');


Route::middleware(['auth'])->group( function(){

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('/organisations', OrganisationController::class)->middleware('adminPage');
    Route::get('/organisation/users/create', [UserController::class, 'create_organisation_user'])->name('organisation.user.create')->middleware('adminPage');
    Route::resource('/indicators', IndicatorController::class);
    Route::patch('/indicators/{id}/response/status/update', [IndicatorController::class, 'updateStatus'])->name('indicators.status.update');
    Route::get('/indicators/{id}/response/create', [ResponseController::class, 'createResponse'])->name('indicators.response.create');
    Route::get('/indicators/{id}/response/edit', [ResponseController::class, 'editResponse'])->name('indicators.response.edit');
    Route::post('/indicators/response/store', [ResponseController::class, 'storeResponse'])->name('indicators.response.store');
    Route::put('/indicators/response/update', [ResponseController::class, 'updateResponse'])->name('indicators.response.update');
    Route::delete('/indicators/response/delete/{id}', [ResponseController::class, 'deleteResponse'])->name('indicators.response.destroy');
    Route::get('/indicators/{id}/responses', [ResponseController::class, 'getResponsesForIndicator'])->name('indicator.responses');
    Route::resource('/users', UserController::class)->middleware('role:admin,user');
    Route::patch('users/{id}/role', [UserController::class, 'updateRole'])->name('users.update.role');
    Route::patch('users/{id}/email', [UserController::class, 'updateEmail'])->name('users.update.email');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::resource('/theory', ThoeryOfChangeController::class);
    Route::get('/theory/{id}/indicators', [ThoeryOfChangeController::class, 'getIndicators'])->name('theory.indicators');
    Route::get('/theory/{id}/indicators/create', [ThoeryOfChangeController::class, 'createIndicatorUsingToC'])->name('theory.indicators.create');
    
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::resource('/archives', ArchivesController::class)->middleware('role:admin,user');;
    Route::get('/organisation/archives', [ArchivesController::class, 'getArchives'])->name('organisation.archives.get'); 
    Route::get('/publications/{id}/listing', [IndicatorController::class, 'getOrganisationPublications'])->name('organisation.publications');
    
    Route::post('/archives/{archive_id}/move-indicator/{indicator_id}', [ArchivesController::class, 'moveArchivedIndicatorToArchive'])->name('archives.moveIndicator');
    Route::get('/archives/indicator/{id}', [ArchivesController::class, 'getIndicator'])->name('archives.indicator.details');
    Route::get('/archives/indicator/{id}/responses', [ArchivesController::class, 'getResponsesForIndicator'])->name('archives.indicator.responses');
    
    Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('profile.show');
    Route::patch('/profile/update/photo', [UserProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::post('/profile/update/profile', [UserProfileController::class, 'updateProfile'])->name('profile.update.profile');
    Route::post('/password/check', [UserProfileController::class, 'checkCurrentPassword'])->name('password.check');
    Route::patch('/password/update', [UserProfileController::class, 'updatePassword'])->name('password.update');
    
    Route::resource('logs', LogController::class)->middleware('role:admin');
    Route::resource('/files', FilesController::class);
    
} );



// Catch-all route for non-existing routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
