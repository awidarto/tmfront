@extends('layout.front')

@section('content')
<style type="text/css">
    label{
        display: block;
        border: none;
    }

    button#submit{
        text-transform: uppercase;
        color: #FFF;
        font-size: 12px;
    }

</style>
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>Shopping Cart</h2>
            <div class="container" style="display:block;font-size:12px;">
                {{ $itemtable }}
                {{ Former::open_vertical($doku_submit,'POST',array('class'=>'custom'))}}
                    <div class="row">
                        <div class="col-md-12">
                            {{ Former::hidden('MALLID')->value($doku_mall_id) }}
                            {{ Former::hidden('CHAINMERCHANT')->value('NA') }}
                            {{ Former::hidden('AMOUNT')->value(number_format($totalcost,2,'.','')) }}
                            {{ Former::hidden('PURCHASEAMOUNT')->value(number_format($totalcost,2,'.','')) }}
                            {{ Former::hidden('TRANSIDMERCHANT')->value($payment_id) }}
                            <!-- unencypted WORDS : {{ $words_plain }} , test only, To Do : remove when production ready -->
                            {{ Former::hidden('WORDS')->value($words) }}
                            {{ Former::hidden('REQUESTDATETIME')->value( $request_time ) }}
                            {{ Former::hidden('CURRENCY')->value('360') }}
                            {{ Former::hidden('PURCHASECURRENCY')->value('360') }}
                            {{ Former::hidden('SESSIONID')->value($payment_session) }}
                            {{ Former::select('PAYMENTCHANNEL','Payment Method')->options(Config::get('doku.channel'))}}
                            {{ Former::text('NAME','Name')->value(Auth::user()->fullname ) }}
                            {{ Former::text('EMAIL','Email')->value( Auth::user()->email ) }}
                            {{ Former::hidden('ADDITIONALDATA')->value('') }}
                            {{ Former::hidden('BASKET')->value($basket) }}
                            {{ Former::text('ADDRESS','Shipping Address')->value(Auth::user()->address) }}
                            {{ Former::text('CITY','City')->value(Auth::user()->city) }}
                            {{ Former::text('STATE','State')->value(Auth::user()->state) }}
                            {{ Former::text('PROVINCE','Province')->value(Auth::user()->state) }}
                            {{ Former::text('COUNTRY','Country')->value(Auth::user()->countryOfOrigin) }}
                            {{ Former::text('ZIPCODE','Zip Code')->value(Auth::user()->zipCode) }}

                            <!-- data taken from Doku example form -->
                            {{ Former::text('MOBILEPHONE','Mobile Phone') }}
                            {{ Former::text('HOMEPHONE','Home Phone') }}
                            {{ Former::text('WORKPHONE','Work Phone') }}
                            {{ Former::text('BIRTHDATE','Birth Date')->placeholder('YYYYMMDD') }}

                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-right" id="submit">Pay Now</button>
                            <a href="{{ URL::to('shop/collection')}}" class="btn btn-danger" id="cancel">Back to Shop</a>
                        </div>
                    </div>
                {{Former::close()}}
            </div>
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){


    });
</script>
@stop