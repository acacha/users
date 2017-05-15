<?php

namespace Acacha\Users\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest.
 *
 * @package Acacha\Users\Http\Requests
 */
class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('create-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|max:255',
            'username' => 'sometimes|required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ];
    }
}
