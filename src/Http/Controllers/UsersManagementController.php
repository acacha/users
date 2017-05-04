<?php

namespace Acacha\Users\Http\Controllers;

use Auth;
use Illuminate\Http\Response;

/**
 * Class UsersManagementController
 *
 * @package App\Http\Controllers
 */
class UsersManagementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('manage-users');
        return view('acacha_users::managment');
    }
}