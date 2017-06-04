<?php

namespace Acacha\Users\Http\Controllers;
use App\User;

/**
 * Class UsersDashboardController.
 *
 * @package Acacha\Users\Http\Controllers
 */
class UsersDashboardController extends Controller
{
    /**
     * Show users dashboard
     */
    public function index()
    {
        $this->authorize('see-users-dashboard');
        $data = [];

        return view('acacha_users::dashboard', $data);
    }

    /**
     * @return int
     */
    public function totalUsers()
    {
//        $this->authorize('obtain-users-dashboard-info');
        return User::all()->count();
    }

    public function totalUserInvitations()
    {

    }

    public function pendingUserInvitations()
    {

    }

    public function acceptedUserInvitations()
    {

    }
}
