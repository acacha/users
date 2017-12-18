<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\LoggedUserPasswordUpdate;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;

/**
* Class APILoggedUserPasswordController
*
* @package Acacha\Users\Http\Controllers
*/
class APILoggedUserPasswordController extends Controller {

    /**
     * Update logged user password.
     *
     * @param LoggedUserPasswordUpdate $request
     * @return JsonResponse|\App\User|null
     */
    public function update(LoggedUserPasswordUpdate $request)
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
        $user->update(['password' => bcrypt($request->password)]);

        return $user;
    }

    /**
     * Check current password
     *
     * @param $user
     * @return bool
     */
    protected function checkCurrentPassword(LoggedUserPasswordUpdate $request, $user)
    {
        return Hash::check($request->current_password, $user->password);
    }
}