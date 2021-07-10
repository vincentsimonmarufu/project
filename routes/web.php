<?php

use App\Http\Controllers\FoodCollectionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('auth.login');
});

Auth::routes();

// Public routes files
Route::group(['middleware' => ['web','activity']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

// admin routes
Route::group(['middleware' => ['web','activity','role:admin']], function () {

    // departments
    Route::resource('departments', 'App\Http\Controllers\DepartmentsController');
    Route::get('assign-manager','App\Http\Controllers\DepartmentsController@assignManager');
    Route::get('get-user-department/{paynumber}','App\Http\Controllers\DepartmentsController@getDepartment');
    Route::post('assign-manager-post','App\Http\Controllers\DepartmentsController@assignManagerPost');

    // job titles
    Route::resource('jobtitles', 'App\Http\Controllers\JobtitlesController');

    // usertypes
    Route::resource('usertypes', 'App\Http\Controllers\UsertypesController');

    // users
    Route::resource('users', 'App\Http\Controllers\UsersManagementController');
    Route::resource('deleted-users', 'App\Http\Controllers\SoftDeleteUsersController');
    Route::get('deactivate-user/{id}','App\Http\Controllers\UsersManagementController@deActivateUser');

    // beneficiaries
    Route::resource('beneficiaries', 'App\Http\Controllers\BeneficiariesController');

    // allocations
    Route::resource('allocations', 'App\Http\Controllers\AllocationsController');
    Route::get('import-allocation','App\Http\Controllers\AllocationsController@allocationImportForm');
    Route::post('allocation-import-send','App\Http\Controllers\AllocationsController@allocationImportSend');
    Route::get('all-alloctions','App\Http\Controllers\AllocationsController@allAllocations');
    Route::get('/department-users/{department}','App\Http\Controllers\AllocationsController@getDepartmentalUsers');
    Route::resource('deleted-allocations', 'App\Http\Controllers\SoftDeleteAllocationsController');
    Route::get('/get-allocation/{paynumber}','App\Http\Controllers\AllocationsController@getAllocation');

    // jobcards
    Route::resource('jobcards', 'App\Http\Controllers\JobcardsController');
    Route::resource('deleted-jobcards','App\Http\Controllers\SoftDeleteJobcardsController');
    Route::get('restore-job/{id}','App\Http\Controllers\SoftDeleteJobcardsController@restoreJob');

    // food request
    Route::resource('frequests','App\Http\Controllers\FoodRequestController');
    Route::get('/getusername/{paynumber}','App\Http\Controllers\FoodRequestController@getUsername');
    Route::get('/approve-request/{id}','App\Http\Controllers\FoodRequestController@approveRequest');
    Route::get('/reject-request/{id}','App\Http\Controllers\FoodRequestController@rejectRequest');

    Route::get('/hsettings-get','App\Http\Controllers\HumberSettingsController@getSettings');
    Route::put('/hsettings-post/{id}','App\Http\Controllers\HumberSettingsController@updateSettings')->name('hsettings');

    Route::get('/approved-requests','App\Http\Controllers\FoodRequestController@getApproved');
    Route::get('/pending-requests','App\Http\Controllers\FoodRequestController@getPending');

    // food collection
    Route::resource('fcollections', FoodCollectionController::class);
});

Route::group(['prefix' => 'activity', 'namespace' => 'jeremykenedy\LaravelLogger\App\Http\Controllers', 'middleware' => ['web', 'auth', 'activity','role:admin']], function () {

    // Dashboards
    Route::get('/', 'LaravelLoggerController@showAccessLog')->name('activity');
    Route::get('/cleared', ['uses' => 'LaravelLoggerController@showClearedActivityLog'])->name('cleared');

    // Drill Downs
    Route::get('/log/{id}', 'LaravelLoggerController@showAccessLogEntry');
    Route::get('/cleared/log/{id}', 'LaravelLoggerController@showClearedAccessLogEntry');

    // Forms
    Route::delete('/clear-activity', ['uses' => 'LaravelLoggerController@clearActivityLog'])->name('clear-activity');
    Route::delete('/destroy-activity', ['uses' => 'LaravelLoggerController@destroyActivityLog'])->name('destroy-activity');
    Route::post('/restore-log', ['uses' => 'LaravelLoggerController@restoreClearedActivityLog'])->name('restore-activity');
});