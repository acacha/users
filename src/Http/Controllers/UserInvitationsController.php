<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\SendInvitationRequest;
use Acacha\Users\Models\UserInvitation;
use App\User;
use Response;

/**
 * Class UserInvitationsController
 *
 * @package App\Http\Controllers
 */
class UserInvitationsController extends Controller
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
     * Send Invitation.
     */
    public function sendInvitation(SendInvitationRequest $request)
    {
        return $this->store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SendInvitationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SendInvitationRequest $request)
    {
        UserInvitation::create(['email' => $request->input('email') ]);

        return Response::json(['created' => true ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list-user-invitations');
        return UserInvitation::paginate();
    }
}