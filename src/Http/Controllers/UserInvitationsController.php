<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserInvited;
use Acacha\Users\Http\Requests\SendInvitationRequest;
use Acacha\Users\Models\UserInvitation;
use Illuminate\Http\Request;
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
        $invitation = UserInvitation::create($request->only(['email','state','token']));

        // NOTE : this method trigger method "created" in UserInvitationObserver. Fire also and event to enable hooking.
        event(new UserInvited($invitation));

        return Response::json(['created' => true ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete-user-invitations');
        UserInvitation::destroy($id);

        // NOTE : this method trigger method "created" in UserInvitationObserver. Fire also and event to enable hooking.
//        event(new UserInvited($invitation));

        return Response::json(['deleted' => true ]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('show-user-invitations');
        return UserInvitation::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->authorize('edit-user-invitations');
        $invitation = UserInvitation::find($id);
        $invitation->update($request->intersect(['email','state','token']));
        return Response::json(['updated' => true ]);
    }

}