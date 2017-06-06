<?php
/*
 * Same configuration as Laravel 5.2 make auth:
 * See https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/routes.stub
 * but take into account we have to add 'web' middleware group here because Laravel by defaults add this middleware in
 * RouteServiceProvider
 */
Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => 'auth'], function() {
        Route::get('/management/users', 'UsersManagementController@users');
        Route::get('/management/users/invitations', 'UserInvitationsController@userInvitations');
        Route::get('/management/users/dashboard', 'UsersDashboardController@index');
        Route::get('/management/users/tracking', function() {
            return view('acacha_users::tracking');
        })->middleware('can:see-users-dashboard');
        Route::get('/user/profile/{user?}', 'UserProfileController@index');
    });
    // "Public" accessible but protected by token
    Route::get('/management/users/user-invitation-accept', 'UserInvitationsController@accept');
    //Public
    Route::get('/users/register/by/email', 'UsersManagementController@registerByEmail');
    if (config('users.users_can_invite_other_users')) {
        Route::get('/invite/user', 'UserInvitationsController@invite');
    }

});

Route::group(['middleware' => 'api','prefix' => 'api/v1', 'middleware' => 'throttle'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        //USERS
        Route::get('/management/users', 'UsersManagementController@index');
        Route::post('/management/users', 'UsersManagementController@store');
        Route::get('/management/users/{User}', 'UsersManagementController@show');
        Route::delete('/management/users/{User}', 'UsersManagementController@destroy');
        Route::put('/management/users/{User}', 'UsersManagementController@update');

        //USER INVITATIONS
        Route::get('/management/users-invitations', 'UserInvitationsController@index');
        //Send and store are the same: emails are sent when new user invitation is stored in database using eloquent events
        Route::post('/management/users-invitations/send', 'UserInvitationsController@sendInvitation');
        Route::post('/management/users-invitations', 'UserInvitationsController@store');

        Route::get('/management/users-invitations/{UserInvitation}', 'UserInvitationsController@show');
        Route::delete('/management/users-invitations/{UserInvitation}', 'UserInvitationsController@destroy');
        Route::put('/management/users-invitations/{UserInvitation}', 'UserInvitationsController@update');

        //USERS DASHBOARD
        Route::get('/management/users-dashboard/totalUsers', 'UsersDashboardController@totalUsers');

        //USERS Tracking
        Route::get('/revisionable/model/tracking', 'RevisionableController@trackModel');

        //Users profile
        Route::get('/user/profile/{user?}', 'UserProfileController@show')->middleware('bindings');

        //User reset password email
        Route::post('/management/users/send/reset-password-email',
            '\App\Http\Controllers\Auth\NoGuestForgotPasswordController@sendResetLinkEmail');
    });

    Route::post('/management/user-invitations-accept', 'UserInvitationsController@postAccept');
    if (config('users.users_can_invite_other_users')) {
        Route::post('/invite/user', 'UserInvitationsController@sendInvitation');
    }
});
