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

// Auth
Auth::routes();

// Forms
Route::name('forms.')->prefix('f/{id}')->group(function () {
    Route::get('/', 'FormsController@show')->name('show');
    Route::post('/', 'FormsController@submit')->name('submit');
});

// Stripe Webhook
Route::post('stripe/webhook', 'StripeWebhookController@handleWebhook');

// App
Route::view('{page?}', 'layouts.app')
     ->where('page', '.*')
     ->middleware('auth');
