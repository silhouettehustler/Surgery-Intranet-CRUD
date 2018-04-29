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


Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/getUser/{id}','HomeController@getUser')->name('get-user');
Route::get('/home/getUserChatDetails', 'HomeController@getUserChatDetails')->name('home-user-chat-details');

Route::get('/appointment', 'AppointmentController@index')->name('appointment');
Route::get('/appointment/edit/{id}', 'AppointmentController@edit')->name('appointment-edit');
Route::get('/appointment/create', 'AppointmentController@create')->name('appointment-create');
Route::get('/appointment/timeSlots','AppointmentController@timeSlots')->name('appointment-get-time-slot');
Route::post('/appointment/destroy/{id}', 'AppointmentController@destroy')->name("appointment-destroy");