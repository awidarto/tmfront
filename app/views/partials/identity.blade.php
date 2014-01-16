@if(Auth::check())
    <div>
     	<p>Welcome {{ Auth::user()->firstname.' '.Auth::user()->lastname }}</p>
        <p><a href="{{ URL::to('logout') }}">Logout</a></p>
    </div>
    <div>
        <p>You have 0 items in your shopping cart</p>
    </div>
@else
    <style type="text/css">
        #identity-box input[type=text],
        #identity-box input[type=password]{
            float: left;
            width: 94px;
            line-height: 20px;
            border: thin solid #000;
            padding: 2px;
            margin: 2px;
        }

        #identity-box input[type=text]#search{
            width:95%;
        }

        #identity-box .btn{
            border: thin solid #000;
            font-size: 11px;
            line-height: 1.3;
            margin-top: 2px;
            padding:5px;
        }
    </style>
    <h2>LOGIN / SIGN UP</h2>

    <div class="col-md-12" style="padding:0px;" id="identity-box">
        {{ Form::open(array('url' => 'login','class'=>'form-inline', 'role'=>'form')) }}
            @if (Session::has('loginError'))
                @if (Session::get('loginError'))
                <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
                     <button type="button" class="close" data-dismiss="alert"></button>
                     Email or password incorrect.
                @endif
            @endif
            {{ Former::text('email','')->placeholder('Email') }}
            {{ Former::password('password','')->placeholder('Password') }}
            {{ Former::submit('Go')->class('btn btn-default btn-sm') }}
        {{ Form::close() }}
        <div class="clearfix"></div>
        {{ Form::open(array('url' => 'search','class'=>'form-inline', 'role'=>'form')) }}
            {{ Former::text('search','')->placeholder('Search')->id('search')->class('search') }}
        {{ Form::close() }}
        <div class="clearfix"></div>
    </div>
@endif
