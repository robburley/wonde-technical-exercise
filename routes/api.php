<?php

use App\Http\Controllers\Schools\Employees\ClassesController;
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

//Route::middleware('auth:sanctum')
//->group(function(){
    Route::get('/school/{school_id}/employee/{employee_id}/classes', [ClassesController::class, 'index']);
//});
