<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MangoProductionController;
use App\Http\Controllers\FarmersDataController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PollQuestionController;
use App\Http\Controllers\PollAnswerController;
use App\Http\Controllers\ParticipantAnswerController;
use App\Http\Controllers\MobilizerController;


Route::match (['get', 'post'], 'mbeere/results', [HomeController::class, 'adminDashboard2'])->name('mbeere.results');
Route::get('mobilizers/dashboard', [MobilizerController::class, 'overallDashboard'])->name('mobilizers.dashboard');


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

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
// Route::get('admin-dashboard', [App\Http\Controllers\HomeController::class, 'adminDashboard']);

Route::match (['get', 'post'], 'admin-dashboard', [HomeController::class, 'adminDashboard'])->name('admin-dashboard')
;
Route::get('store-dashboard', [App\Http\Controllers\HomeController::class, 'storeDashboard']);

Route::get('/check-config', function () {
    return response()->json([
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
    ]);
});
//users
Route::resource('users', UserController::class);

// polls
Route::resource('polls', PollController::class);
Route::resource('poll-questions', PollQuestionController::class);
Route::resource('poll-answers', PollAnswerController::class);
Route::resource('participant-answers', ParticipantAnswerController::class);
Route::get('participant-answers/users/addPoll/{id}', [App\Http\Controllers\ParticipantAnswerController::class, 'addPoll'])->name('participant-answers.addPoll');
Route::get('participant-answers/users/addPoll2/{id}', [App\Http\Controllers\ParticipantAnswerController::class, 'addPoll2'])->name('participant-answers.addPoll2');
Route::post('participant-answers/update', [App\Http\Controllers\ParticipantAnswerController::class, 'update'])->name('participant-answers.update');

// Add these routes to routes/web.php

// routes/web.php
// Route::get('mobilizers/dashboard', [MobilizerController::class, 'overallDashboard'])->name('mobilizers.dashboard');
// AJAX filter for index
Route::post('mobilizers/filter', [MobilizerController::class, 'filter'])->name('mobilizers.filter');
Route::get('/get-super-agent/{id}', [MobilizerController::class, 'getSuperAgent']);


// Dashboard route for per role
Route::match(['get', 'post'], 'mobilizers/dashboard/{roleId}', [MobilizerController::class, 'dashboard'])->name('mobilizers.dashboard.role');

// AJAX routes for form
// Route::get('get-wards/{subCountyId}', [MobilizerController::class, 'getWards']);
Route::get('get-market-details/{marketId}', [MobilizerController::class, 'getMarketDetails']);

// Resource routes for mobilizers CRUD
Route::resource('mobilizers', MobilizerController::class);

// // Resource routes for mobilizers CRUD
// Route::post('mobilizers/filter', [MobilizerController::class, 'filter'])->name('mobilizers.filter');

// // Dashboard route
// Route::match(['get', 'post'], 'mobilizers/dashboard', [MobilizerController::class, 'dashboard'])->name('mobilizers.dashboard');
// Route::resource('mobilizers', MobilizerController::class);
// // Additional route for AJAX filtering on index (called via POST or GET for data fetch)
// // Existing AJAX filter route

// Route::get('mobilizers/dashboards/small-committee', [MobilizerController::class, 'smallCommitteeDashboard'])->name('mobilizers.dashboards.small');
// Route::get('mobilizers/dashboards/expanded-committee', [MobilizerController::class, 'expandedCommitteeDashboard'])->name('mobilizers.dashboards.expanded');
// Route::get('mobilizers/dashboards/kivui-business', [MobilizerController::class, 'kivuiBusinessCommitteeDashboard'])->name('mobilizers.dashboards.kivui');
// // Route::match(['get', 'post'], 'mobilizers/dashboard', [MobilizerController::class, 'dashboard'])->name('mobilizers.dashboard');

Route::group(['middleware' => ['auth']], function () {


Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::post('user.update-call', [App\Http\Controllers\UserController::class, 'updateCall'])->name('users.update-call');
Route::post('user.update_participant', [App\Http\Controllers\UserController::class, 'update_participant'])->name('users.update_participant');
Route::post('user.assign-user', [App\Http\Controllers\UserController::class, 'assignUser'])->name('users.assign-user');

// Route::get('user.participants', [App\Http\Controllers\UserController::class, 'participants'])->name('users.participants');
Route::match (['get', 'post'], 'user.participants', [UserController::class, 'participants'])->name('user.participants');

// Route::get('user.agents', [App\Http\Controllers\UserController::class, 'agents'])->name('users.agents');

Route::match (['get', 'post'], 'user.agents', [UserController::class, 'agents'])->name('users.agents');


Route::get('user.all', [App\Http\Controllers\UserController::class, 'all'])->name('users.all');
Route::match (['get', 'post'], 'users.all2', [UserController::class, 'contacts'])->name('users.all2');
// Web route for the page UI
Route::get('contacts', [UserController::class, 'contacts'])->name('contacts.page');

// Server-side AJAX route
Route::post('contacts/data', [UserController::class, 'contactsData'])->name('contacts.data');



Route::get('user.agentCalls/{id}', [App\Http\Controllers\UserController::class, 'agentCalls'])->name('user.agentCalls');
Route::get('user.agentCallsPicked/{id}', [App\Http\Controllers\UserController::class, 'agentCallsPicked'])->name('user.agentCallsPicked');
Route::get('user.mycalls', [App\Http\Controllers\UserController::class, 'mycalls'])->name('users.mycalls');
Route::match (['get', 'post'], 'user.mycalls', [UserController::class, 'mycalls'])->name('user.mycalls');
Route::get('user.declined', [App\Http\Controllers\UserController::class, 'declined'])->name('users.declined');
Route::get('user.picked', [App\Http\Controllers\UserController::class, 'picked'])->name('users.picked');
Route::get('user.pending', [App\Http\Controllers\UserController::class, 'pending'])->name('users.pending');
Route::get('user.notreached', [App\Http\Controllers\UserController::class, 'notreached'])->name('users.notreached');
Route::get('users/search', [UserController::class, 'search'])->name('users.search');
Route::get('/get-subCountyt/{countyId}', [App\Http\Controllers\UserController::class, 'getSubCountyByCounty']);
Route::get('/get-wards/{subCountyId}', [App\Http\Controllers\UserController::class, 'getWardsBySubCounty']);
Route::get('/subcounty-info/{subCountyId}', [App\Http\Controllers\UserController::class, 'getSubCountyInfo']);


Route::post('/check-existing-data', [App\Http\Controllers\UserController::class, 'checkExistingData'])->name('checkExisting');

//Mango Production
Route::resource('mango_production', MangoProductionController::class);
Route::get('mango_production.view_all', [MangoProductionController::class, 'viewAll'])->name('mango_production.view_all');
Route::get('mango_production.pending', [MangoProductionController::class, 'pending'])->name('mango_production.pending');

//Famers Data
Route::resource('farmers_data', FarmersDataController::class);
Route::get('farmers_data.view_all', [FarmersDataController::class, 'viewAll'])->name('farmers_data.view_all');
Route::get('farmers_data.pending', [FarmersDataController::class, 'pending'])->name('farmers_data.pending');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
});