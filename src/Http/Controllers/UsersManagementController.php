<?php

namespace Acacha\Users\Http\Controllers;

use App\User;
use Response;

/**
 * Class UsersManagementController
 *
 * @package App\Http\Controllers
 */
class UsersManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function web()
    {
        $this->authorize('manage-users');
        return view('acacha_users::managment');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return User::paginate();
    }

    /**
     * Send Invitation.
     */
    public function sendInvitation()
    {
        $this->authorize('send-user-invitations');
        $this->authorize('manage-users');
        dump('hey');
    }
}