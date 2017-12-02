<?php

Route::group(['middleware' => 'api','prefix' => 'api/v1', 'middleware' => 'throttle'], function () {
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
        Route::get('/user/profile/{user?}', 'UserProfileController@show')->middleware('bindings');

        //User reset password email
        Route::post('/management/users/send/reset-password-email',
            'UsersManagementController@sendResetLinkEmail');
//        Route::post('/management/users/send/reset-password-email',
//            '\App\Http\Controllers\Auth\NoGuestForgotPasswordController@sendResetLinkEmail');
        Route::post('/management/users/send/reset-password-email/massive',
            'UsersManagementController@massiveSendResetLinkEmail');

        //Users migration
        Route::get('/management/users-migration/totalNumberOfUsers',
            'UsersMigrationController@totalNumberOfUsers');
        Route::get('/management/users-migration/totalNumberOfMigratedUsers',
            'UsersMigrationController@totalNumberOfMigratedUsers');
        Route::get('/management/users-migration/totalNumberOfUsers/destination',
            'UsersMigrationController@totalNumberOfUsersOnDestination');
        Route::get('/management/users-migration/totalNumberOfMigratedUsers/destination',
            'UsersMigrationController@totalNumberOfMigratedUsersOnDestination');

        Route::get('/management/users-migration/checkConnection', 'UsersMigrationController@checkConnection');

        Route::post('/management/users-migration/migrate', 'UsersMigrationController@migrate');

        Route::post('/management/users-migration/migrate-stop', 'UsersMigrationController@stopMigration');
        Route::get('/management/users-migration/migrate-resume', 'UsersMigrationController@resumeMigration');

        Route::get('/management/users-migration/history', 'UsersMigrationController@history');
        Route::get('/management/users-migration/batch_history', 'UsersMigrationController@batchHistory');

        Route::get('/management/users-google/check', 'GoogleAppsUsersController@check');
        Route::get('/management/users-google/local-sync', 'GoogleAppsUsersController@localSync');

        Route::get('/management/users-google', 'GoogleAppsUsersController@all');

    });

    Route::post('/management/user-invitations-accept', 'UserInvitationsController@postAccept');
    if (config('users.users_can_invite_other_users')) {
        Route::post('/invite/user', 'UserInvitationsController@sendInvitation');
    }
});
