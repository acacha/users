@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">

            <model-tracking model="App\User"></model-tracking>

        </div>
    </div>
@endsection