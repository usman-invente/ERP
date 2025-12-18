<?php


use Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController;
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

Route::middleware('web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu')->prefix('dynamic-qr-code')->group(function () {
    
    // Route::get('generate-qr', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'generateQr']);
    Route::get('list', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'index'])->name('dynamic_qr_code_list');
    Route::get('generate-qr', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'create']);
    Route::post('generate-qr', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'store']);
    Route::get('edit-dyn-qr-code/{link}', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'edit'])->name('dynamic_qr_code_edit');    
    Route::put('update-dyn-qr-code/{link}', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'update']);
   
    Route::get('list-dynamic-qr-codes', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'getListDynamicQrCodes']);
});
Route::get('redirect-dyn-qr-code/{link}', [\Modules\DynamicQrCode\Http\Controllers\DynamicQrCodeController::class, 'redirectQRCode'])->name('dynamic_qr_code_redirect')->middleware('count.views');
