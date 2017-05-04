<?php

namespace Acacha\Users\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Class UsersManagmentController
 *
 * @package App\Http\Controllers
 */
class UsersManagmentController extends Controller
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
        return view('acacha_users::managment');
    }
}