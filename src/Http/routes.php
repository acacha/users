<?php
/*
 * Same configuration as Laravel 5.2 make auth:
 * See https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/routes.stub
 * but take into account we have to add 'web' middleware group here because Laravel by defaults add this middleware in
 * RouteServiceProvider
 */
Route::group(['middleware' => 'web'], function () {
    Route::get('/management/users', 'UsersManagementController@web');
});

Route::group(['middleware' => 'api','prefix' => 'api/v1'], function () {
    Route::middleware('auth:api')->get('/management/users', 'UsersManagementController@index');
    Route::middleware('auth:api')->post('/management/users/send/invitation', 'UsersManagementController@sendInvitation');
});
