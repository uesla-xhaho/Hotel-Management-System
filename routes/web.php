<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\PublicBookingController::class, 'index'])->name('public-booking');
Route::get('/availability', [App\Http\Controllers\PublicBookingController::class, 'search'])->name('public-availability');
Route::post('/public-booking', [App\Http\Controllers\PublicBookingController::class, 'store'])->name('public-booking.store');

Auth::routes();
Route::middleware('isAdmin')->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});


Route::get('/hotel', [App\Http\Controllers\HotelController::class, 'index'])->name('hotel');
Route::get('/hotel2', [App\Http\Controllers\HotelController::class, 'page2'])->name('hotel2');
Route::post('/addhotel', [App\Http\Controllers\HotelController::class, 'store'])->name('addhotel');
Route::post('/hotel-edit', [App\Http\Controllers\HotelController::class, 'edit'])->name('hotel-edit');
Route::post('/hotel-delete', [App\Http\Controllers\HotelController::class, 'destroy'])->name('hotel-delete');


Route::get('/rooms', [App\Http\Controllers\RoomsController::class, 'index'])->name('rooms');
Route::get('/rooms/{room_id}', [App\Http\Controllers\RoomsController::class, 'addrooms'])->name('rooms2');
Route::post('/add', [App\Http\Controllers\RoomsController::class, 'store'])->name('add');
Route::post('/room-edit', [App\Http\Controllers\RoomsController::class, 'edit'])->name('room-edit');
Route::post('/deleterooms', [App\Http\Controllers\RoomsController::class, 'destroy'])->name('deleterooms');

Route::get('/staff', [App\Http\Controllers\StaffController::class, 'index'])->name('staff');
Route::get('/staff2', [App\Http\Controllers\StaffController::class, 'page2'])->name('staff2');
Route::post('/staffadd', [App\Http\Controllers\StaffController::class, 'store'])->name('staffadd');
Route::post('/staff-edit', [App\Http\Controllers\StaffController::class, 'edit'])->name('staff-edit');
Route::post('/staff-delete', [App\Http\Controllers\StaffController::class, 'destroy'])->name('staff-delete');

Route::get('/customer', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer');
Route::get('/customer-add', [App\Http\Controllers\CustomerController::class, 'page2'])->name('customer-add');
Route::post('/customer-store', [App\Http\Controllers\CustomerController::class, 'store'])->name('customer-store');
Route::post('/customer-edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customer-edit');
Route::post('/customer-delete', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('customer-delete');



Route::get('/mail/{passwords}/{username}', [App\Http\Controllers\MailController::class, 'sendMail'])->name('mail');


Route::get('/booking', [App\Http\Controllers\BookingController::class, 'index'])->name('booking');
Route::get('/booking2', [App\Http\Controllers\BookingController::class, 'page2'])->name('booking2');
Route::post('/bookingadd', [App\Http\Controllers\BookingController::class, 'store'])->name('bookingadd');
Route::get('/booking3/{booking_id}', [App\Http\Controllers\BookingController::class, 'chooseroom'])->name('booking3');
Route::get('/booking/{id}', [App\Http\Controllers\BookingController::class, 'cancel'])->name('booking4');
Route::get('/booking_com/{id}', [App\Http\Controllers\BookingController::class, 'complete'])->name('completebooking');
Route::get('/booking/{room_id}/{bid}', [App\Http\Controllers\BookingController::class, 'selectroom'])->name('bookings');


Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment');
Route::get('/invoice/{id}', [App\Http\Controllers\PaymentController::class, 'invoice'])->name('invoice');
Route::post('/payment-complete', [App\Http\Controllers\PaymentController::class, 'complete'])->name('payment-complete');
Route::post('/payment-delete', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('payment-delete');




//user
Route::get('/user/home', [App\Http\Controllers\HomeController::class, 'userIndex'])->name('user-home');
Route::get('/user/rooms', [App\Http\Controllers\RoomsController::class, 'userIndex'])->name('user-rooms');
Route::get('/user/booking', [App\Http\Controllers\BookingController::class, 'userIndex'])->name('user-booking');
Route::get('/user/booking3/{booking_id}', [App\Http\Controllers\BookingController::class, 'roomIndex'])->name('choose-room');
Route::get('/user/customer', [App\Http\Controllers\CustomerController::class, 'userIndex'])->name('user-customer');
Route::get('/user/payment', [App\Http\Controllers\PaymentController::class, 'userIndex'])->name('user-payment');
