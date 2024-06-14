<?php

use Illuminate\Support\Facades\Route;
use Modules\Financial\app\Http\Controllers\FinancialController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(
    function ()
    {
        Route::apiResource('financial', FinancialController::class)->names('financial');
    },
);