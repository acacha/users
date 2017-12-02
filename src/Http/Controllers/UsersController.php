<?php

namespace Acacha\Users\Http\Controllers;

use Response;

/**
 * Class UsersController
 *
 * @package Acacha\Users\Http\Controllers
 */
class UsersController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('see-manage-users-view');
        return view('acacha_users::management');
    }

}