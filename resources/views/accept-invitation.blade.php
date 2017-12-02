@extends('adminlte::layouts.errors')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <create-user-via-invitation
            api-url="{{ env('APP_URL') . '/api/v1/user-invitations-accept' }}"
            email="{{ $email }}" token="{{ $token }}"></create-user-via-invitation>
@endsection