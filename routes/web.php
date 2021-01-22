<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function(){return redirect('/login');});


Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['adminAuth', 'web']], function () {

    /**
     * USERS
     */
    Route::get('users', [UserController::class, 'index']);
    Route::get('user/create', [UserController::class, 'create']);
    Route::post('user/save',  [UserController::class, 'save']);
	Route::get('user/readOnly/{id}', [UserController::class, 'readOnly']);
	Route::get('user/edit/{id}', [UserController::class, 'edit']);
    Route::get('user/delete/{id}', [UserController::class, 'delete']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::get('user/search', [UserController::class, 'search']);


    /**
     * BOOKINGS
     */
    Route::get('bookings', [BookingController::class, 'index']);
    Route::get('booking/delete/{id}', [BookingController::class, 'delete']);
    Route::get('booking/search', [BookingController::class, 'search']);

    /**
     * EVENTS
     */
    Route::get('events', [EventController::class, 'index']);
    Route::get('event/create', [EventController::class, 'create']);
    Route::post('event/save',  [EventController::class, 'save']);
	Route::get('event/readOnly/{id}', [EventController::class, 'readOnly']);
	Route::get('event/edit/{id}', [EventController::class, 'edit']);
    Route::get('event/delete/{id}', [EventController::class, 'delete']);
    Route::post('event/update', [EventController::class, 'update']);
    Route::get('event/search', [EventController::class, 'search']);


     /**
     * PAYMENTS
     */
    Route::get('payments', [PaymentController::class, 'index']);
	Route::get('payment/readOnly/{id}', [PaymentController::class, 'readOnly']);
    Route::get('payment/delete/{id}', [PaymentController::class, 'delete']);

    Route::get('logout',[LoginController::class, 'logout']);

});

