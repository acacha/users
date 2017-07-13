<?php

namespace Acacha\Users\Services\GoogleApps;

use Acacha\Users\Models\GoogleApps\GoogleUser;
use Cache;
use Google;

/**
 * Class GoogleAppsService.
 *
 * @package Acacha\Users\Services\GoogleApps
 */
class GoogleAppsService
{

    /**
     * Get connection state.
     *
     * @return string
     */
    public function getConnectionState()
    {
        $directory = Google::make('directory');

        try {
            $r = $directory->users->get($email = config('users.google_apps_user_email_to_check_connection'));
            if($r) {
                $state = 'connected';
            } else {
                echo 'User does not exists: $email<br/>';
            }
        } catch (\Exception $e) {
            $state = 'error';
            $message = json_decode($e->getMessage());
        }

        $result = [
            'state' => $state
        ];

        if ($state != 'connected') $result['message'] = $message;

        return $result;
    }

    /**
     * Get total number of users.
     */
    public function totalNumberOfUsers()
    {
        return count($this->allUsers());
    }

    /**
     * Get all users from google apps.
     *
     * @return array|mixed
     */
    protected function allUsers()
    {
        $directory = Google::make('directory');
        $pageToken = null;
        $users = [];
        do {
            try {
                $r = $directory->users->listUsers([
                    'domain' => config('users.google_apps_domain'),
                    'maxResults' => config('users.google_apps_users_maxResults'),
                    'pageToken' => $pageToken
                ]);
                $pageToken = $r->nextPageToken;
                $users = array_merge($users, $r->users);
            } catch (\Exception $e) {
                return json_decode($e->getMessage());
            }
        } while ($pageToken);
        return $users;
    }

    /**
     * Sync local database with remote Google apps info.
     */
    public function localSync()
    {
        $users = Cache::rememberForever('google_app_users', function () {
//            return $this->getUsers();
            return $this->allUsers();
        });

        foreach ($users as $user) {
//            dd($user);
            $user = GoogleUser::firstOrCreate([
                'customerId' => $user->customerId,
                'kind' => $user->kind,
                'google_id'=> $user->id,
                'etag'=> $user->etag,
                'primaryEmail'=> $user->primaryEmail,
                'givenName'=> $user->name->givenName,
                'familyName'=> $user->name->familyName,
                'fullName'=> $user->name->fullName,
                'orgUnitPath'=> $user->orgUnitPath,
                'organizations'=> json_decode($user->organizations),
                'isAdmin'=> $user->isAdmin,
                'isDelegatedAdmin'=> $user->isDelegatedAdmin,
                'lastLoginTime'=> $user->lastLoginTime,
                'creationTime'=> $user->creationTime,
                'deletionTime'=> $user->deletionTime,
                'agreedToTerms'=> $user->agreedToTerms,
                'password'=> $user->password,
                'hashFunction'=> $user->hashFunction,
                'suspended'=> $user->suspended,
                'suspensionReason'=> $user->suspensionReason,
                'changePasswordAtNextLogin'=> $user->changePasswordAtNextLogin,
                'emails'=> json_encode($user->emails),
            ]);
//            dd($user);
        }
    }

    /**
     * Get users.
     *
     * @param int $perPage
     * @return int|mixed
     */
    public function getUsers($perPage = 15)
    {
        $directory = Google::make('directory');
        try {
            $r = $directory->users->listUsers([
                'domain' => config('users.google_apps_domain'),
                'maxResults' => $perPage
            ]);
        } catch (\Exception $e) {
            return json_decode($e->getMessage());
        }

        return $r->users;
    }
}