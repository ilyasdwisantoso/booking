<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\ImageUploadController;

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


Route::post('/qrcode', [AttendanceController::class, 'verify']);
Route::post('/upload-photo', [AttendanceController::class, 'uploadPhoto']);
Route::post('/access', [AttendanceController::class, 'verifyAccess']);




Route::put('/photo', [MahasiswaController::class, 'update']);
Route::post('/qr_code', [QRCodeController::class, 'store']);
Route::post('/attendance/mark', [AttendanceController::class, 'markAttendance']);
