<?php

use App\Http\Controllers\ArchivesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThoeryOfChangeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPreferenceController;
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



Auth::routes(['verify' => true, 'register' => false]);

Route::post('/password/update', [ResetPasswordController::class, 'update'])->name('user.update.password');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/organisations/all', [OrganisationController::class, 'getOrganisations'])->name('organisations.all')->middleware('role:root');
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
    Route::resource('/users', UserController::class)->middleware('role:root,admin,user');
    Route::patch('users/{id}/role', [UserController::class, 'updateRole'])->name('users.update.role');
    Route::patch('users/{id}/organisation', [UserController::class, 'updateOrganisation'])->name('users.update.organisation')->middleware('role:root');
    Route::patch('users/{id}/email', [UserController::class, 'updateEmail'])->name('users.update.email');
    Route::patch('/user/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::resource('/theory', ThoeryOfChangeController::class);
    Route::get('/theory/{id}/indicators', [ThoeryOfChangeController::class, 'getIndicators'])->name('theory.indicators');
    Route::get('/theory/{id}/indicators/create', [ThoeryOfChangeController::class, 'createIndicatorUsingToC'])->name('theory.indicators.create');

    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::resource('/archives', ArchivesController::class)->middleware('role:root,admin,user,viewer');
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

    Route::resource('logs', LogController::class)->middleware('role:root,admin');
    Route::resource('/files', FilesController::class);
    Route::get('/response/files/{responseId}', [FilesController::class, 'getResponseFiles'])->name('reponse.files');

    Route::get('/indicators/export', [IndicatorController::class, 'exportAllWithResponses'])->name('indicators.export.all');
    Route::get('/indicators/{id}/export', [IndicatorController::class, 'exportIndicatorAndResponses'])->name('indicators.export.single');
    Route::get('/indicators/pdf/{id}', [IndicatorController::class, 'exportIndicatorPDF'])->name('export.single.indicator.pdf');
    Route::get('/indicators/csv/{id}', [IndicatorController::class, 'exportIndicatorAndResponsesAsCSV'])->name('export.single.indicator.csv');

    //Routes for charts
    Route::get('/indicator/{indicatorId}/graph/line', [IndicatorController::class, 'getLineChartData'])->name('indicator.linegraph');

    Route::get('/account/settings', [UserPreferenceController::class, 'showPreferences'])->name('preferences.show');
    Route::put('/preferences/update', [UserPreferenceController::class, 'updatePreference'])->name('preferences.update');
    Route::get('/email/request-verification/{id}', [EmailVerificationController::class, 'sendVerification'])->name('verification.request');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('/events/calendar', [EventsController::class, 'showCalender'])->name('calendar');
    Route::get('/events/display/{visibility}', [EventsController::class, 'getEvents'])->name('all-events');
    Route::resource('/events', EventsController::class)->middleware('role:root,admin');
});

Route::get('/verify-security-question', [LoginController::class, 'verifySecurityQuestion'])->name('verify.security_question');
Route::post('/verify-security-question', [LoginController::class, 'checkSecurityQuestionAnswer'])->name('verify.security_question.check');


// Catch-all route for non-existing routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
