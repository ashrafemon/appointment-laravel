<?php

use App\Http\Controllers\Api\Admin\Staff\BreakHourController as AdminStaffBreakHourController;
use App\Http\Controllers\Api\Admin\Staff\DetailsController as AdminStaffDetailsController;
use App\Http\Controllers\Api\Admin\Staff\ServiceController as AdminStaffServiceController;
use App\Http\Controllers\Api\Admin\Staff\TimeoffController as AdminStaffTimeoffController;
use App\Http\Controllers\Api\Admin\Staff\WorkingHourController as AdminStaffWorkingHourController;
use App\Http\Controllers\Api\Admin\Staff\WorkingStatusController as AdminStaffWorkingStatusController;
use App\Http\Controllers\Api\AppointController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\Staff\BreakHourController as StaffBreakHourController;
use App\Http\Controllers\Api\Staff\DetailsController as StaffDetailsController;
use App\Http\Controllers\Api\Staff\ServiceController as StaffServiceController;
use App\Http\Controllers\Api\Staff\TimeoffController as StaffTimeoffController;
use App\Http\Controllers\Api\Staff\WorkingHourController as StaffWorkingHourController;
use App\Http\Controllers\Api\Staff\WorkingStatusController as StaffWorkingStatusController;
use Illuminate\Support\Facades\Route;

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

/*
| Auth Routes
    login, register, logout
| Profile Routes
    index, update, change_password, upload_avatar
*/
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forget', [AuthController::class, 'forget']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::get('/profile', [ProfileController::class, 'index']);
Route::patch('/profile', [ProfileController::class, 'update']);
Route::patch('/profile/password/change', [ProfileController::class, 'change_password']);
Route::post('/profile/avatar/upload', [ProfileController::class, 'upload_avatar']);

/*
| Category Routes
    index, store, show, update, delete
| Service Routes
    index, store, show, update, delete
*/
Route::apiResource('/category', CategoryController::class)->except(['create', 'edit']);
Route::apiResource('/service', ServiceController::class)->only(['index', 'store', 'destroy']);
Route::post('/service/{slug}', [ServiceController::class, 'update']);

/*
| Staff Routes Protected by Admin without GET Method
    Details     -> index, store, show, update, delete
                   upload_avatar
    Service     -> index, store
    WorkingHour -> index, update
    BreakTime   -> index, store, show, update, delete
    TimeOff     -> index, store, show, update, delete
*/
Route::apiResource('/staff', AdminStaffDetailsController::class)->except(['create', 'edit']);
Route::post('/staff/{id}/avatar/upload', [AdminStaffDetailsController::class, 'upload_avatar']);
Route::apiResource('/staff/{id}/service', AdminStaffServiceController::class)->only(['index', 'store']);
Route::apiResource('/staff/{user_id}/working-hour', AdminStaffWorkingHourController::class)->only(['index', 'update']);
Route::apiResource('/staff/{user_id}/break-time', AdminStaffBreakHourController::class)->except('create', 'edit');
Route::apiResource('/staff/{user_id}/time-off', AdminStaffTimeoffController::class)->except('create', 'edit');
Route::apiResource('/staff/{user_id/working-status', AdminStaffWorkingStatusController::class)->only(['index']);

/*
| Appoint Routes
    store
*/
Route::apiResource('/appoint', AppointController::class)->only(['index', 'store']);
Route::get('/appoint/self', [AppointController::class, 'userAppoints']);

/*
| Self Staff Routes
    Details     -> index, update
                   change_password, upload_avatar
    Service     -> store
    WorkingHour -> update
    BreakTime   -> store, update, delete
    TimeOff     -> store, update, delete
*/
Route::prefix('self/staff')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [StaffDetailsController::class, 'index']);
    Route::patch('/', [StaffDetailsController::class, 'update']);
    Route::patch('/password/change', [StaffDetailsController::class, 'change_password']);
    Route::post('/avatar/upload', [StaffDetailsController::class, 'upload_avatar']);

    Route::post('/service', [StaffServiceController::class, 'store']);
    Route::apiResource('/working-hour', StaffWorkingHourController::class)->only(['update']);
    Route::apiResource('/break-time', StaffBreakHourController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('/time-off', StaffTimeoffController::class)->only(['store', 'update', 'destroy']);

    Route::apiResource('/working-status', StaffWorkingStatusController::class)->only(['store', 'update']);
    Route::get('/working-status/latest', [StaffWorkingStatusController::class, 'latestStatus']);
});
