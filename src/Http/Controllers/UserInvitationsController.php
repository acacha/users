<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserCreated;
use Acacha\Users\Events\UserInvited;
use Acacha\Users\Http\Requests\CreateUserWithTokenRequest;
use Acacha\Users\Http\Requests\SendInvitationRequest;
use Acacha\Users\Http\Requests\UpdateInvitationRequest;
use Acacha\Users\Models\UserInvitation;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Response;

/**
 * Class UserInvitationsController
 *
 * @package Acacha\Users\Http\Controllers
 */
class UserInvitationsController extends Controller
{
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
        $invitation = UserInvitation::where(['email' => $request->input('email')])->first();
        if (!$invitation) {
            $invitation = UserInvitation::create($request->only(['email','state','token']));
        }

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
        $this->authorize('view-user-invitations');
        return UserInvitation::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvitationRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvitationRequest $request, $id)
    {
        $invitation = UserInvitation::find($id);
        $invitation->update($request->intersect(['email','state','token']));
        return Response::json(['updated' => true ]);
    }

    /**
     * Accept user invitation.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accept(Request $request)
    {
        if (! $request->has('token')) abort(404);
        if (! $invitation = $this->validateToken($request->input('token'))) abort(404);
        return $this->showAcceptUserInvitationForm($invitation);
    }

    /**
     * Process accept user invitation form.
     *
     * @param CreateUserWithTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAccept(CreateUserWithTokenRequest $request)
    {
        if (! $request->has('token')) abort(403);
        if (! $invitation = $this->validateToken($request->input('token'))) abort(403);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        event(new UserCreated($user));

        $invitation = UserInvitation::where('token', $request->input('token'))->first();
        $invitation->user()->associate($user);
        $invitation->accept();

        return Response::json(['created' => true ]);
    }

    /**
     * Validate token.
     *
     * @param $token
     * @return bool
     */
    protected function validateToken($token)
    {
        if (!$token) return false;
        try {
            $invitation = UserInvitation::where('token', $token)
                ->where('state', 'pending')->first();
            if ($invitation) {
                if ( $invitation->token === $token) return $invitation;
            }
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * Show accept user invitation form.
     *
     * @param $invitation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function showAcceptUserInvitationForm($invitation)
    {
        $data = [
            'email' => $invitation->email,
            'token' => $invitation->token
        ];
        return view('acacha_users::accept-invitation',$data);
    }

    /**
     * Show the management user invitations page.
     *
     * @return Response
     */
    public function userInvitations()
    {
        $this->authorize('see-manage-user-invitations-view');
        return view('acacha_users::management-invitations');
    }

    /**
     * Show the user invitations public page.
     *
     * @return Response
     */
    public function invite()
    {
        if (!config('users.users_can_invite_other_users')) abort(404);
        return view('acacha_users::invite-user');
    }

}