<?php

namespace Acacha\Users\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MassiveDestroyRequest.
 *
 * @package Acacha\Users\Http\Requests
 */
class MassiveDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('massive-delete-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids'     => 'required'
        ];
    }
}
