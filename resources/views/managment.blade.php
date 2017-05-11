@extends('adminlte::page')

@section('htmlheader_title')
    {{ trans('acacha_users_lang::message.usersmanagment-htmlheader-title') }}
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Users Management</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <users-management api-url="{{ env('APP_URL') . env('API_URI','/api/v1/management/users') }}"></users-management>
                </div>
            </div>
        </div>
    </div>
@endsection