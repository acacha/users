<?php

namespace Acacha\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * Class APIForgotPasswordController.
 *
 * @package Acacha\Users\Http\Controllers
 */
class APIForgotPasswordController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if (Password::RESET_LINK_SENT) {
            return new JsonResponse(['status' => trans($response) ], 200);
        }

        return new JsonResponse(['email' => trans($response) ], 422);

    }

    /**
     * Send a reset link to the given users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function massiveSendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['ids' => 'required']);

        $errors = [];
        foreach ($request->input('ids') as $id) {
            $user = User::find($id);
            $response = Password::broker()->sendResetLink([ 'email' => $user->email ]);
            if (! Password::RESET_LINK_SENT) {
                dd('ERROR!');
                $errors[] = $response;
            }
        }

        if ( count($errors) > 0 ) return new JsonResponse(['status' => 'Error', 'errors' => $errors ], 422);

        return new JsonResponse(['status' => 'Done' ], 200);
    }
}
