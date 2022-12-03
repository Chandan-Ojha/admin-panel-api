<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

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

/*
 *  User Credentials
 */
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('get-employee-list',[EmployeeController::class,'get_employee_list']);
    Route::get('get-single-employee-info/{emp_id}',[EmployeeController::class,'get_single_employee_info']);
    Route::post('create-employee',[EmployeeController::class,'create_employee']);
    Route::put('update-employee/{emp_id}',[EmployeeController::class,'update_employee']);
    Route::delete('delete-employee/{emp_id}',[EmployeeController::class,'delete_employee']);
    Route::post('/logout',[AuthController::class,'logout']);
});


