<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\LoggedUserEmailUpdate;
use Acacha\Users\Http\Requests\LoggedUserPasswordUpdate;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;

/**
* Class APILoggedUserEmailController
*
* @package Acacha\Users\Http\Controllers
*/
class APILoggedUserEmailController extends Controller {

    /**
     * Update logged user password.
     *
     * @param LoggedUserEmailUpdate $request
     * @return JsonResponse|\App\User|null
     */
    public function update(LoggedUserEmailUpdate $request)
    {
        $user = Auth::user();
        if (! $this->checkCurrentPassword($request, $user)) {
            return new JsonResponse(
                [
                  'message'=> 'The given data was invalid.',
                  'errors'=> [
                    'current_password' => [ 'The password is not valid']
                  ]
                ],
                422
            );
        }
        $user->update(['email' => $request->email]);

        return $user;
    }

    /**
     * Check current password
     *
     * @param $user
     * @return bool
     */
    protected function checkCurrentPassword(LoggedUserEmailUpdate $request, $user)
    {
        return Hash::check($request->current_password, $user->password);
    }
}