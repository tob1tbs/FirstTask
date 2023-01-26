<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\MainController::class, 'actionMainIndex'])->name('actionMainIndex');

// USER ROUTES
Route::post('/login', [App\Http\Controllers\MainController::class, 'actionMainLogin'])->name('actionMainLogin');
Route::post('/register', [App\Http\Controllers\MainController::class, 'actionMainRegister'])->name('actionMainRegister');
Route::post('/make/order', [App\Http\Controllers\MainController::class, 'actionMakeOrder'])->name('actionMakeOrder');
Route::post('/make/order/payment', [App\Http\Controllers\MainController::class, 'actionMakeOrderPayment'])->name('actionMakeOrderPayment');
Route::post('/make/order/payment/suggest', [App\Http\Controllers\MainController::class, 'actionMakeOrderPaymentSuggest'])->name('actionMakeOrderPaymentSuggest');