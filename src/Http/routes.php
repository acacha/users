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

        //Users migration
        Route::get('management/users-migration', 'UsersMigrationController@index')->name('users-migration');

        //Google apps
        Route::get('/management/users/google', 'GoogleAppsUsersController@index');
        Route::get('/management/users/google2', 'GoogleAppsUsersController@check');
        Route::get('/management/users/google1', 'GoogleAppsUsersController@esborrar');
        Route::get('/management/users/google3', 'GoogleAppsUsersController@google3');
        Route::get('/management/users/google4', 'GoogleAppsUsersController@google4');
        Route::get('/management/users/google8', 'GoogleAppsUsersController@google8');
        Route::get('/management/users/google10', 'GoogleAppsUsersController@google10');

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
