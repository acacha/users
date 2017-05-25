<?php

namespace Acacha\Users\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserRequest.
 *
 * @package Acacha\Users\Http\Requests
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'sometimes|required|max:255',
            'username' => 'sometimes|required|max:255|unique:users',
            'email' => 'sometimes|required|email|max:255|unique:users',
            'password' => 'sometimes|required|min:6'
        ];
    }
}
