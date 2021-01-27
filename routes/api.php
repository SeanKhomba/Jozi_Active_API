<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\BookingController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['api', 'cors']], function () {

    /**
     * USERS
     */
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('forgotPassword', [UserController::class, 'forgotPassword']);
    Route::post('getMobileNumber',  [UserController::class, 'getMobileNumber']);
    Route::post('passwordReset',  [UserController::class, 'passwordReset']);
    Route::post('getUserAccount',  [UserController::class, 'getUserAccount']);
    Route::post('updateAccount',  [UserController::class, 'updateAccount']);
    Route::post('checkAccountStatus',  [UserController::class, 'checkAccountStatus']);
    Route::post('captureAccountActivation',  [UserController::class, 'captureAccountActivation']);

    
    /**
     * EVENTS
     */
    Route::post('getEvents', [EventController::class, 'getEvents']);

    /**
     * BOOKINGS
     */
  // Route::post('captureBooking', [BookingController::class, 'captureBooking']);


    Route::post('symlink', [UserController::class, 'symlink']);


});

Route::middleware('api.auth')->group(function () {
    Route::post('captureBooking', [BookingController::class, 'captureBooking']);
    });
