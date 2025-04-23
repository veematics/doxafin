<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/clients/{client}', [ClientController::class, 'getClientDetails']);
    Route::get('/csv-data/{dataName}', [App\Http\Controllers\Api\CsvDataController::class, 'show']);

    Route::post('/po/check-unique', [PurchaseOrderController::class, 'checkPoUnique']);

});