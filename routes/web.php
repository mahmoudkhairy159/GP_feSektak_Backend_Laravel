<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/showCarForm', 'ProfilesController@showCarForm')->name('car.showCarForm');
    Route::post('/fillCarDetails/{user_id}', 'ProfilesController@fillDetails')->name('car.fillDetails');

    Route::resource('/requests', 'RequestsController');
    Route::resource('/rides', 'RidesController');
    Route::get('/requests/{id}/AvailableRides', 'RequestsController@viewAvailableRides')->name('requests.viewAvailableRides');
    Route::get('/requests/{request_id}/AvailableRides/{ride_id}', 'RequestsController@sendRequest')->name('requsetts.sendRequest');
    Route::get('/requests/{request_id}/myRide/{ride_id}', 'RequestsController@cancelRide')->name('requests.cancelRide');

    //
    Route::get('/rides/{id}/AvailableRequests', 'RidesController@viewSentRequests')->name('rides.viewSentRequests');
    Route::get('/rides/{request_id}/AvailableRequests/{ride_id}', 'RidesController@acceptRequest')->name('rides.acceptRequest');

    //
    Route::get('/profiles/{user_id}/profile', 'ProfilesController@showProfile')->name('users.showProfile');
    Route::get('/profiles/{user_id}/profile/edit', 'ProfilesController@edit')->name('profile.edit');
    Route::post('/profiles/{user_id}/profile/edit', 'ProfilesController@update')->name('profile.update');
});
