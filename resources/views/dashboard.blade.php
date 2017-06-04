@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">

            <users-dashboard></users-dashboard>

            <adminlte-vue-box color="primary">

            </adminlte-vue-box>
        </div>
    </div>
@endsection