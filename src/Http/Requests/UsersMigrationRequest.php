<?php

namespace Acacha\Users\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UsersMigrationRequest.
 *
 * @package Acacha\Users\Http\Requests
 */
class UsersMigrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('migrate-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'users.*.id' => 'sometimes|numeric',
            'filter' => 'sometimes|in:foo,bar'
        ];
    }
}
