<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserCreated;
use Acacha\Users\Events\UserRemoved;
use Acacha\Users\Http\Requests\CreateUserRequest;
use Acacha\Users\Http\Requests\MassiveDestroyRequest;
use Acacha\Users\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Password;
use Response;

/**
 * Class APIFullUsersController
 *
 * @package Acacha\Users\Http\Controllers
 */
class APIFullUsersController extends Controller
{
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
     * Massive destroy.
     *
     * @param MassiveDestroyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massiveDestroy(MassiveDestroyRequest $request)
    {
        $this->authorize('massive-delete-users');
        return $this->executeDestroy($request->input('ids'));
    }

    /**
     * Execute destroy.
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse
     */
    private function executeDestroy($ids){

        $models = User::find($ids);

        User::destroy($ids);

        event(new UserRemoved($models->toJson()));

        return Response::json(['deleted' => true ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|array  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->authorize('delete-users');

        return $this->executeDestroy($id);
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