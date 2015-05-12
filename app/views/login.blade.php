@extends('layout.front')

@section('content')
            <!-- if there are login errors, show them here -->
@if(Auth::check())
    <p class="navbar-text pull-right">
        Hello {{ Auth::user()->fullname }}
        <a href="{{ URL::to('logout')}}" >Logout</a>
    </p>
@endif

<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        {{ Form::open(array('url' => 'login','class'=>'form-signin')) }}
            <h2 class="form-signin-heading">Please sign in</h2>
            @if (Session::has('login_errors'))
                @if (Session::get('loginError'))
                <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
                     <button type="button" class="close" data-dismiss="alert"></button>
                     Email or password incorrect.
                @endif
            @endif
            <p>
                {{ $errors->first('email') }}
                {{ $errors->first('password') }}
            </p>

            <p>
                {{ Form::label('email', 'Email Address') }}
                {{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com','class'=>'form-control col-md-8 ')) }}
            </p>
            <p>
                {{ Form:: label('password', 'Password') }}
                {{ Form::password('password', array('class'=>'form-control col-md-8') ) }}
            </p>

            <br />
            <br />
            <p>
                {{ Form::submit('Login',array('class'=>'btn btn-primary')) }}
            </p>
        {{ Form::close() }}

    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

    </div>
</div>


@stop