@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <users-invitations api-url="{{ env('APP_URL') . env('API_URI','/api/v1/management/users') }}" :collapsed-list="false"></users-invitations>
@endsection