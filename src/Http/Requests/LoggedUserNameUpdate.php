<?php

namespace Acacha\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoggedUserNameUpdate.
 *
 * @package Acacha\Users\Http\Requests
 */
class LoggedUserNameUpdate extends FormRequest
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
            'name'     => 'required|max:255'
        ];
    }
}
