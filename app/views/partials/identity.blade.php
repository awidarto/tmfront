@if(Auth::check())
    <div>
      	<p>Welcome {{ Auth::user()->firstname.' '.Auth::user()->lastname }}</p>
        <p><a href="{{ URL::to('logout') }}">Logout</a></p>
    </div>
    <div>
        <p>You have <span id="cart-qty" >{{ Commerce::getCartItemCount(Auth::user()->activeCart,Config::get('site.outlet_id') )}}</span> items in your shopping cart<br />
            <a class="pull-right" href="{{ URL::to('shop/cart')}}"><i class="fa fa-shopping-cart"></i> View Cart</a>
        </p>
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
                @endif
            @endif
            {{ Former::text('email','')->placeholder('Email')->id('login-email') }}
            {{ Former::password('password','')->placeholder('Password')->id('login-pass') }}
            {{ Former::submit('Go')->class('btn btn-default btn-sm') }}
        {{ Form::close() }}
        <div class="clearfix"></div>
        {{ Form::open(array('url' => 'search','class'=>'form-inline', 'role'=>'form')) }}
            {{ Former::text('search','')->placeholder('Search')->id('search')->class('search') }}
        {{ Form::close() }}
        <div class="clearfix"></div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('input#login-email').removeClass('form-control');
            $('input#login-pass').removeClass('form-control');
        });
    </script>
@endif
