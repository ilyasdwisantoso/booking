<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\Admin\AccessControlController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/attendance/verify-student', [AttendanceController::class, 'verifyStudent']);
Route::post('/attendance/upload-photo', [AttendanceController::class, 'uploadPhoto']);
Route::post('/access/verify', [AccessControlController::class, 'verifyAccess']);

Route::get('/realtime-attendances', [AttendanceController::class, 'getRealtimeAttendances']);




