@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <adminlte-vue-box color="primary">
                <span slot="title">Users Management</span>
                <users-management api-url="{{ env('APP_URL') . env('API_URI','/api/v1/users') }}"></users-management>
            </adminlte-vue-box>
        </div>
    </div>
@endsection