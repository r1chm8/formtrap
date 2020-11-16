<?php

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

/*
 * Field Types
 */
Route::name('field-types.')->prefix('field-types')->group(function () {
    Route::get('/', 'FieldTypesController@index')->name('index');
    Route::get('{id}', 'FieldTypesController@show')->name('show');
});

/*
 * Forms
 */
Route::name('forms.')->prefix('forms')->group(function () {
    Route::get('/', 'FormsController@index')->name('index');
    Route::post('/', 'FormsController@store')->name('store');
    Route::get('{id}', 'FormsController@show')->name('show');
    Route::patch('{id}', 'FormsController@update')->name('update');
    Route::delete('{id}', 'FormsController@destroy')->name('destroy');

    // Versions
    Route::name('versions.')->prefix('{formId}/versions')->group(function () {
        Route::get('/', 'FormVersionsController@index')->name('index');
        Route::post('/', 'FormVersionsController@store')->name('store');
        Route::get('{id}', 'FormVersionsController@show')->name('show');
        Route::patch('{id}', 'FormVersionsController@update')->name('update');
        Route::delete('{id}', 'FormVersionsController@destroy')->name('destroy');

        // Fields
        Route::name('fields.')->prefix('{versionId}/fields')->group(function () {
            Route::get('/', 'FieldsController@index')->name('index');
            Route::post('/', 'FieldsController@store')->name('store');
            Route::get('{id}', 'FieldsController@show')->name('show');
            Route::patch('{id}', 'FieldsController@update')->name('update');
            Route::delete('{id}', 'FieldsController@destroy')->name('destroy');

            // Move
            Route::put('{id}/move', 'FieldsController@move');
        });
    });
});

/*
 * Enquiries
 */
Route::name('enquiries.')->prefix('enquiries')->group(function () {
    Route::get('/', 'EnquiriesController@index')->name('index');
    Route::get('{id}', 'EnquiriesController@show')->name('show');
    Route::patch('{id}', 'EnquiriesController@update')->name('update');
    Route::delete('{id}', 'EnquiriesController@destroy')->name('destroy');
});

/*
 * Subscriptions
 */
Route::name('subscriptions.')->prefix('subscription')->group(function () {
    Route::put('/', 'SubscriptionsController@subscribe')->name('subscribe');
    Route::delete('/', 'SubscriptionsController@unsubscribe')->name('unsubscribe');
});

/*
 * Users
 */
Route::name('user.')->prefix('user')->group(function () {
    Route::get('/', 'UsersController@show')->name('show');
    Route::patch('/', 'UsersController@update')->name('update');
});
