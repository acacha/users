<?php

namespace Acacha\Users\Http\Controllers;
use App\User;
use Auth;

/**
 * Class UserProfileController.
 *
 * @package Acacha\Users\Http\Controllers
 */
class UserProfileController
{
    /**
     * Show user profile.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        $data = [];
        if ($user->id) {
            //Requesting another user profile
            if ( $user->identifiableName() === Auth::user()->id || Auth::user()->can('see-other-users-profile')) {
                $data = $user->toArray();
            } else {
                abort(403);
            }
        }
        return view('acacha_users::profile', $data);
    }

    /**
     * Show user profile info by api.
     *
     * @param User $user
     * @return User|null
     */
    public function show(User $user)
    {
        if ($user->id) {
            return $user;
        }
        return Auth::user();
    }

    /**
     * Check authorization to see other users profile.
     */
    private function checkAuthorizationToSeeOtherUsersProfile()
    {

    }
}