@if(Auth::check())
    <div>
     	<p>Welcome {{ Auth::user()->firstname.' '.Auth::user()->lastname }}</p>
        <p><a href="{{ URL::to('logout') }}">Logout</a></p>
    </div>
    <div>
        <p>You have 0 items in your shopping cart</p>
    </div>
@else
    <h2>LOGIN / SIGN UP</h2>
    <div class="col-md-12" style="padding:0px;">
        {{ Form::open(array('url' => 'login','class'=>'form-inline', 'role'=>'form')) }}
                @if (Session::has('loginError'))
                    @if (Session::get('loginError'))
                    <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
                         <button type="button" class="close" data-dismiss="alert"></button>
                         Email or password incorrect.
                    @endif
                @endif
                <div class="form-group" >
                    {{ $errors->first('email') }}
                    {{ $errors->first('password') }}
                </div>

                <div class="form-group" >
                    {{ Form::text('email', Input::old('email'), array('placeholder' => 'your email')) }}
                    {{ Form::password('password',array('placeholder' => 'your password')) }}
                    {{ Form::submit('Go',array('class'=>'btn btn-default btn-sm')) }}
                </div>

                <label class="checkbox">
                  <input type="checkbox" value="remember-me"> Remember me
                </label>
        {{ Form::close() }}
    </div>
@endif
