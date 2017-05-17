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
    Route::get('/management/users', 'UsersManagementController@index');
    Route::post('/management/users', 'UsersManagementController@store');
    //Send and store are the same: emails are sent when new user invitation is stored in database using eloquent events
    Route::post('/management/users/invitations/send', 'UserInvitationsController@sendInvitation');
    Route::post('/management/users/invitations', 'UserInvitationsController@store');
    Route::get('/management/users/invitations', 'UserInvitationsController@index');
    Route::get('/management/users/invitations/{UserInvitation}', 'UserInvitationsController@show');
    Route::delete('/management/users/invitations/{UserInvitation}', 'UserInvitationsController@destroy');
    Route::put('/management/users/invitations/{UserInvitation}', 'UserInvitationsController@update');
});
