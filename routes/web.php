<?php

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


Auth::routes();
Route::get('/', function () {
    return redirect('login');
});

Route::get('/home', function () {
    return redirect('logs');
});

Route::get('/logs', 'LogsController@show');

Route::get('/attendance', 'AttendanceController@show');
Route::post('/remarks', 'AttendanceController@addRemarks');
Route::post('/remarks/delete', 'AttendanceController@deleteRemarks');
Route::post('/remarks2/delete', 'AttendanceController@deleteRemarks2');
Route::get('/attendance/export', 'AttendanceController@export');
Route::post('/approval', 'AttendanceController@approval');

#EMPLOYEE MAIN
Route::get('/employees', 'EmployeeController@show');
Route::get('/employees/{employee}', 'EmployeeController@schedule');
Route::get('/search','EmployeeController@search');

#SCHEDULE
Route::post('/employees/{employee}', 'ScheduleController@create');
Route::get('/employees/edit/{employee}/{id}', 'ScheduleController@edit');
Route::post('/employees/{employee}/{id}', 'ScheduleController@delete');
Route::post('/employees/update/{employee}/{id}', 'ScheduleController@update');
Route::post('/createAll', 'ScheduleController@createAll');

Route::get('/summary/{employee}', 'SummaryController@show');

Route::get('/users/', 'UserController@show');
Route::post('/users/create', 'UserController@create');
Route::get('/users/edit', 'UserController@edit');
Route::post('/users/update', 'UserController@update');

