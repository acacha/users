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

