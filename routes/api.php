<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\USBController;
use Illuminate\Http\Request;
use App\Http\Controllers\AgentController;
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/check-token', function (Request $request) {
        return response()->json(['success' => true]);
    });

    Route::get('/usb/files', [USBController::class, 'listFiles']);        
    Route::post('/usb/upload', [USBController::class, 'uploadFile']);     
    Route::get('/usb/download/{filename}', [USBController::class, 'downloadFile']); 

});


