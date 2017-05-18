<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserCreated;
use Acacha\Users\Http\Requests\CreateUserRequest;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list-users');
        return User::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->only('name', 'email', 'password'));

        event(new UserCreated($user));

        return Response::json(['created' => true ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete-users');
        User::destroy($id);

        // NOTE : this method trigger method "created" in UserInvitationObserver. Fire also and event to enable hooking.
//        event(new UserRemoved($user));

        return Response::json(['deleted' => true ]);
    }

}