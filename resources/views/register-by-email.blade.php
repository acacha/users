@extends('acacha_users::layouts.fullscreen')

@section('htmlheader_title')
    Register user by email
@endsection

@section('htmlheader_css')
    {{-- TODO <link rel="stylesheet" type="text/css" href="/css/todo.css" />--}}
@endsection

@section('main-content')
    <div class="container">
        <div class="codrops-top clearfix">
            <a class="codrops-icon codrops-icon-prev" href="http://www.iesebre.com"><span>Come back www.iesebre.com</span></a>
            <span class="right"><a class="codrops-icon codrops-icon-drop" href="http://www.iesebre.com/ebre-escool"><span>Ebre-escool</span></a></span>
        </div>
        <header class="codrops-header">
            <h1>New user registration <span>www.iesebre.com</span></h1>
        </header>
        <section>
            <register-user-by-email
                    api-url="{{ env('APP_URL') . '/api/v1/management/user-invitations-accept' }}">
            </register-user-by-email>

        </section>
    </div>

@endsection

@section('scripts')
    {{-- TODO <script src="js/prova.js"></script>--}}
@endsection