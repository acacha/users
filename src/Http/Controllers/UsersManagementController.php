<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\SendInvitationRequest;
use Acacha\Users\Models\UserInvitation;
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
        $this->authorize('see-manage-users-view');
        return view('acacha_users::managment');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $this->authorize('list-users');
        return User::paginate();
    }

    /**
     * Send Invitation.
     */
    public function sendInvitation(SendInvitationRequest $request)
    {
        dd($request->input('email'));
        UserInvitation::create(['email' => $request->input('email') ]);
    }
}