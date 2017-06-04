<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserCreated;
use Acacha\Users\Http\Requests\CreateUserRequest;
use Acacha\Users\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use Response;

/**
 * Class UsersManagementController
 *
 * @package Acacha\Users\Http\Controllers
 */
class UsersManagementController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function users()
    {
        $this->authorize('see-manage-users-view');
        return view('acacha_users::management');
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
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

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

        //TODO
        // NOTE : this method trigger method "created" in UserObserver. Fire also and event to enable hooking.
//        event(new UserRemoved($user));

        return Response::json(['deleted' => true ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view-users');
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->intersect(['email','name','password']));
        return Response::json(['updated' => true ]);
    }

    /**
     * Register user by email
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerByEmail(Request $request)
    {
        $data = [];
        return view('acacha_users::register-by-email',$data);
    }

}