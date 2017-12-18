<?php

Route::group([ 'prefix' => 'api/v1', 'middleware' => ['api','bindings','throttle']], function () {
    Route::group(['middleware' => 'auth:api'], function() {

        //USER
        Route::get('/user', 'APILoggedUserController@index');
        Route::put('/user', 'APILoggedUserController@update');

        //User password:
        Route::put('/user/password', 'APILoggedUserPasswordController@update');

        //User name
        Route::put('/user/name', 'APILoggedUserNameController@update');

        //User email
        Route::put('/user/email', 'APILoggedUserEmailController@update');

        //USERS
        Route::get('/users', 'APIFullUsersController@index');
        Route::post('/users', 'APIFullUsersController@store');
        Route::get('/users/{User}', 'APIFullUsersController@show');
        Route::post('/users-massive', 'APIFullUsersController@massiveDestroy');
        Route::put('/users/{User}', 'APIFullUsersController@update');
        Route::delete('/users/{User}', 'APIFullUsersController@destroy');

        //USER INVITATIONS
        Route::get('/users-invitations', 'UserInvitationsController@index');
        //Send and store are the same: emails are sent when new user invitation is stored in database using eloquent events
        Route::post('/users-invitations/send', 'UserInvitationsController@sendInvitation');
        Route::post('/users-invitations', 'UserInvitationsController@store');

        Route::get('/users-invitations/{UserInvitation}', 'UserInvitationsController@show');
        Route::delete('/users-invitations/{UserInvitation}', 'UserInvitationsController@destroy');
        Route::put('/users-invitations/{UserInvitation}', 'UserInvitationsController@update');

        //USERS DASHBOARD
        Route::get('/users-dashboard/totalUsers', 'UsersDashboardController@totalUsers');

        //USERS Tracking
        Route::get('/revisionable/model/tracking', 'RevisionableController@trackModel');

        //Users profile
        Route::get('/user/profile/{user?}', 'UserProfileController@show');

        //User reset password email
        Route::post('/users/send/reset-password-email',
            'APIForgotPasswordController@sendResetLinkEmail');
//        Route::post('/users/send/reset-password-email',
//            '\App\Http\Controllers\Auth\NoGuestForgotPasswordController@sendResetLinkEmail');
        Route::post('/users/send/reset-password-email/massive',
            'APIForgotPasswordController@massiveSendResetLinkEmail');

        //Google apps
        Route::get('/users-google/check', 'GoogleAppsUsersController@check');
        Route::get('/users-google/local-sync', 'GoogleAppsUsersController@localSync');

        Route::get('/users-google', 'GoogleAppsUsersController@all');

    });

    Route::post('/user-invitations-accept', 'UserInvitationsController@postAccept');
    if (config('users.users_can_invite_other_users')) {
        Route::post('/invite/user', 'UserInvitationsController@sendInvitation');
    }
});
