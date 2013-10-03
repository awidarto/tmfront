@extends('layout.main')

@section('content')
            <!-- if there are login errors, show them here -->
@if(Auth::check())
    <p class="navbar-text pull-right">
        Hello {{ Auth::user()->fullname }}
        <a href="{{ URL::to('logout')}}" >Logout</a>
    </p>
@endif

{{ Form::open(array('url' => 'login')) }}
    <h1>Login</h1>

    <!-- if there are login errors, show them here -->
    @if (Session::get('loginError'))
    <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
    @endif

    <p>
        {{ $errors->first('email') }}
        {{ $errors->first('password') }}
    </p>

    <p>
        {{ Form::label('email', 'Email Address') }}
        {{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
    </p>
    <p>
        {{ Form:: label('password', 'Password') }}
        {{ Form::password('password') }}
    </p>

    <p>{{ Form::submit('Submit!') }}</p>
{{ Form::close() }}

@stop