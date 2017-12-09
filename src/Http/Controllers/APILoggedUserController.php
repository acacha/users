<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\LoggedUserUpdate;
use Auth;

/**
* Class APILoggedUserController
*
* @package Acacha\Users\Http\Controllers
*/
class APILoggedUserController extends Controller {

    /**
    * Show the logged user info.
    *
    * @return Response
    */
    public function index()
    {
        return Auth::user();
    }

    /**
     * Update logged user.
     *
     * @return Response
     */
    public function update(LoggedUserUpdate $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name','email','password']));

        return $user;
    }
}