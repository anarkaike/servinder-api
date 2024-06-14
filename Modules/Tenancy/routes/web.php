<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenancy\app\Http\Controllers\TenancyController;

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

Route::group(
    [],
    function ()
    {
        Route::resource('tenancy', TenancyController::class)->names('tenancy');
    },
);
