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
    Route::post('department-import-send','App\Http\Controllers\DepartmentsController@departmentImportSend');
    Route::get('/department-download','App\Http\Controllers\DepartmentsController@downloadDepartmentForm');
    Route::get('get-department-import', 'App\Http\Controllers\DepartmentsController@importDepartments');
    Route::get('assign-manager','App\Http\Controllers\DepartmentsController@assignManager');
    Route::get('get-user-department/{paynumber}','App\Http\Controllers\DepartmentsController@getDepartment');
    Route::post('assign-manager-post','App\Http\Controllers\DepartmentsController@assignManagerPost');

    // job titles
    Route::resource('jobtitles', 'App\Http\Controllers\JobtitlesController');

    // usertypes
    Route::resource('usertypes', 'App\Http\Controllers\UsertypesController');

    // users
    Route::resource('users', 'App\Http\Controllers\UsersManagementController');
    Route::post('users-import-send','App\Http\Controllers\UsersManagementController@usersImportSend');
    Route::get('/users-download','App\Http\Controllers\UsersManagementController@downloadUsersForm');
    Route::get('get-users-import', 'App\Http\Controllers\UsersManagementController@importUsers');
    Route::resource('deleted-users', 'App\Http\Controllers\SoftDeleteUsersController');
    Route::get('deactivate-user/{id}','App\Http\Controllers\UsersManagementController@deActivateUser');
    Route::get('terminate-user-form','App\Http\Controllers\UsersManagementController@terminateForm');
    Route::get('reset-pin','App\Http\Controllers\UsersManagementController@resetPinForm');
    Route::post('reset-post','App\Http\Controllers\UsersManagementController@resetPinPost');
    Route::post('terminate-post','App\Http\Controllers\UsersManagementController@terminatePost');

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
    Route::get('/allocation-download','App\Http\Controllers\AllocationsController@downloadAllocationForm');

    // jobcards
    Route::resource('jobcards', 'App\Http\Controllers\JobcardsController');
    Route::resource('deleted-jobcards','App\Http\Controllers\SoftDeleteJobcardsController');
    Route::get('restore-job/{id}','App\Http\Controllers\SoftDeleteJobcardsController@restoreJob');
    Route::get('get-jobcard-import','App\Http\Controllers\JobcardsController@importJobcards');
    Route::post('import-jobcard','App\Http\Controllers\JobcardsController@uploadJobcards');
    Route::get('/jobcard-download','App\Http\Controllers\JobcardsController@downloadJobcardForm');

    // food request
    Route::resource('frequests','App\Http\Controllers\FoodRequestController');
    Route::get('/getusername/{paynumber}','App\Http\Controllers\FoodRequestController@getUsername');
    Route::get('/approve-request/{id}','App\Http\Controllers\FoodRequestController@approveRequest');
    Route::get('/reject-request/{id}','App\Http\Controllers\FoodRequestController@rejectRequest');

    Route::get('/hsettings-get','App\Http\Controllers\HumberSettingsController@getSettings');
    Route::put('/hsettings-post/{id}','App\Http\Controllers\HumberSettingsController@updateSettings')->name('hsettings');

    Route::get('/approved-requests','App\Http\Controllers\FoodRequestController@getApproved');
    Route::get('/pending-requests','App\Http\Controllers\FoodRequestController@getPending');
    Route::get('/collected-requests','App\Http\Controllers\FoodRequestController@getCollectedRequests');

    Route::get('/get-allocation-request/{paynumber}','App\Http\Controllers\FoodRequestController@getAllocation');
    Route::get('/get-daily-approval','App\Http\Controllers\FoodRequestController@dailyApproval');
    Route::post('/get-daily-post','App\Http\Controllers\FoodRequestController@dailyApprovalSearch')->name("daily");

    // food collection
    Route::resource('fcollections', 'App\Http\Controllers\FoodCollectionController');
    Route::get('get-food-request/{id}','App\Http\Controllers\FoodCollectionController@getFoodRequest');
    Route::get('getfrequestallocation/{id}','App\Http\Controllers\FoodCollectionController@getFoodRequestAllocation');
    Route::get('getuserbeneficiaries/{id}','App\Http\Controllers\FoodCollectionController@getUserBeneficiaries');
    Route::get('/get-jobcard-request/{id}','App\Http\Controllers\FoodCollectionController@getRequestJobcard');

    // meat collection
    Route::resource('mcollections', 'App\Http\Controllers\MeatCollectionController');
    Route::get('/get-request-type/{id}','App\Http\Controllers\MeatCollectionController@getRequestType');

    // Reports
    Route::get('user-collection-report','App\Http\Controllers\ReportsController@getUserCollection');
    Route::post('user-collection-post','App\Http\Controllers\ReportsController@postUserCollection');

    Route::get('get-month-report','App\Http\Controllers\ReportsController@getMonthReport');
    Route::post('get-month-post','App\Http\Controllers\ReportsController@postMonthReport');

});

Route::group(['prefix' => 'activity', 'namespace' => 'jeremykenedy\LaravelLogger\App\Http\Controllers', 'middleware' => ['web', 'auth', 'activity','role:admin']], function () {

    // Activity
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


Route::get('email-approve/{id}/{approver}','App\Http\Controllers\FoodRequestController@emailApprove');

Route::group(['middleware' => ['web','activity']], function () {

    Route::get('my-user-allocation','App\Http\Controllers\UserController@myAllocations');

    Route::resource('frequests', 'App\Http\Controllers\FoodRequestController', [
        'only' => [
            'create',
        ],
    ]);
});
