<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\LoggedUserNameUpdate;
use Acacha\Users\Http\Requests\LoggedUserPasswordUpdate;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;

/**
* Class APILoggedUserDataController
*
* @package Acacha\Users\Http\Controllers
*/
class APILoggedUserNameController extends Controller {

    /**
     * Update logged user password.
     *
     * @param LoggedUserNameUpdate $request
     * @return JsonResponse|\App\User|null
     */
    public function update(LoggedUserNameUpdate $request)
    {
        $user = Auth::user();
        $user->update($request->only('name'));
        return $user;
    }
}