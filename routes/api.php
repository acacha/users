<?php

Route::group([ 'prefix' => 'api/v1', 'middleware' => ['api','bindings,throttle']], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        //USERS
        Route::get('/management/users', 'UsersManagementController@index');
        Route::post('/management/users', 'UsersManagementController@store');
        Route::get('/management/users/{User}', 'UsersManagementController@show');
        Route::delete('/management/users/{User}', 'UsersManagementController@destroy');
        Route::post('/management/users-massive', 'UsersManagementController@massiveDestroy');
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
        Route::get('/user/profile/{user?}', 'UserProfileController@show');

        //User reset password email
        Route::post('/management/users/send/reset-password-email',
            'UsersManagementController@sendResetLinkEmail');
//        Route::post('/management/users/send/reset-password-email',
//            '\App\Http\Controllers\Auth\NoGuestForgotPasswordController@sendResetLinkEmail');
        Route::post('/management/users/send/reset-password-email/massive',
            'UsersManagementController@massiveSendResetLinkEmail');

        //Google apps
        Route::get('/management/users-google/check', 'GoogleAppsUsersController@check');
        Route::get('/management/users-google/local-sync', 'GoogleAppsUsersController@localSync');

        Route::get('/management/users-google', 'GoogleAppsUsersController@all');

    });

    Route::post('/management/user-invitations-accept', 'UserInvitationsController@postAccept');
    if (config('users.users_can_invite_other_users')) {
        Route::post('/invite/user', 'UserInvitationsController@sendInvitation');
    }
});
