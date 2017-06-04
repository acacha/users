@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">



            <user-profile api-url="{{ env('APP_URL') }}/api/v1/user/profile" :id="{{ $id  or Auth::user()->id }}"></user-profile>

        </div>
    </div>
@endsection