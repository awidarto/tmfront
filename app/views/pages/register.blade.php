@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>Sign Up</h2>
            <div style="display:block;font-size:12px;">
                {{ Former::open_for_files_horizontal('signup','POST',array('class'=>'custom'))}}
                @if (Session::get('signupError'))
                    <div class="alert alert-danger">{{ Session::get('signupError') }}</div>
                         <button type="button" class="close" data-dismiss="alert"></button>
                @endif

                {{ Former::text('email','Email') }}

                {{ Former::password('pass','Password')->help('Repeat exactly below') }}
                {{ Former::password('repass','Repeat Password') }}

                {{ Former::select('salutation')->options(Config::get('kickstart.salutation'))->label('Salutation')->class('col-md-2 form-control') }}
                {{ Former::text('firstname','First Name') }}
                {{ Former::text('lastname','Last Name') }}

                {{ Former::text('phone','Phone') }}

                {{ Former::text('address','Address') }}
                {{ Former::text('city','City') }}
                {{ Former::text('zipCode','ZIP / Postal Code')->id('zip')->class('col-md-2 form-control')->maxlength(5) }}
                {{ Former::text('state','State / Province')->class('outside col-md-6 form-control')->id('other_state') }}

                {{ Former::select('countryOfOrigin')->id('country')->options(Config::get('country.countries'))->label('Country of Origin') }}

                {{ Former::submit('Sign Up')->class('col-md-12 form-control btn-info')}}

                {{ Former::close() }}
            </div>
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
@stop