<?php

namespace Acacha\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoggedUserEmailUpdate.
 *
 * @package Acacha\Users\Http\Requests
 */
class LoggedUserEmailUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required',
            'email'  => 'required|email|max:255|unique:users'
        ];
    }
}
