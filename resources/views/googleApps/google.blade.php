@extends('adminlte::page')

@section('htmlheader_title')
    Google Apps Users Management
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">

            <google-apps-dashboard user-admin="{{ config('users.google_apps_admin_user_email') }}"></google-apps-dashboard>

            <google-apps-users-list api-url="{{ env('APP_URL') . env('API_URI','/api/v1/users') }}"></google-apps-users-list>

        </div>
    </div>
@endsection