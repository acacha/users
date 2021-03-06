<?php

namespace Acacha\Users\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SendInvitationRequest.
 *
 * @package Acacha\Users\Http\Requests
 */
class SendInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if (config('users.users_can_invite_other_users')) return true;
        if (Auth::user()) return Auth::user()->can('send-user-invitations');
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:users',
        ];
    }
}
