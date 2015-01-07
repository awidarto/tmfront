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
                {{ Former::open_for_files_vertical($doku_submit,'POST',array('class'=>'custom'))}}
                    <div class="row">
                        <div class="col-md-12">
                            {{ Former::text('MALLID','MALLID')->value($doku_mall_id) }}
                            {{ Former::text('CHAINMERCHANT','CHAINMERCHANT')->value('NA') }}
                            {{ Former::text('AMOUNT','AMOUNT')->value(number_format($totalcost,2,'.','')) }}
                            {{ Former::text('PURCHASEAMOUNT','PURCHASEAMOUNT')->value(number_format($totalcost,2,'.','')) }}
                            {{ Former::text('TRANSIDMERCHANT','TRANSIDMERCHANT')->value($payment_id) }}
                            <!-- unencypted WORDS : {{ $words_plain }} , test only, To Do : remove when production ready -->
                            {{ Former::text('WORDS','WORDS')->value($words) }}
                            {{ Former::text('REQUESTDATETIME','REQUESTDATETIME')->value( $request_time ) }}
                            {{ Former::text('CURRENCY','CURRENCY')->value('360') }}
                            {{ Former::text('PURCHASECURRENCY','PURCHASECURRENCY')->value('360') }}
                            {{ Former::text('SESSIONID','SESSIONID')->value($payment_session) }}
                            {{ Former::select('PAYMENTCHANNEL','PAYMENTCHANNEL')->options(Config::get('doku.channel'))}}
                            {{ Former::text('NAME','NAME')->value(Auth::user()->fullname ) }}
                            {{ Former::text('EMAIL','EMAIL')->value( Auth::user()->email ) }}
                            {{ Former::text('ADDITIONALDATA','ADDITIONALDATA') }}
                            {{ Former::text('BASKET','BASKET')->value($basket) }}
                            {{ Former::text('ADDRESS','SHIPPING_ADDRESS')->value(Auth::user()->address) }}
                            {{ Former::text('CITY','CITY')->value(Auth::user()->city) }}
                            {{ Former::text('STATE','STATE')->value(Auth::user()->state) }}
                            {{ Former::text('PROVINCE','PROVINCE')->value(Auth::user()->state) }}
                            {{ Former::text('COUNTRY','COUNTRY')->value(Auth::user()->countryOfOrigin) }}
                            {{ Former::text('ZIPCODE','ZIPCODE')->value(Auth::user()->zipCode) }}

                            <!-- data taken from Doku example form -->
                            {{ Former::text('MOBILEPHONE','MOBILEPHONE')->value('0217998391') }}
                            {{ Former::text('HOMEPHONE','HOMEPHONE')->value('0217998391') }}
                            {{ Former::text('WORKPHONE','WORKPHONE')->value('0217998391') }}
                            {{ Former::text('BIRTHDATE','BIRTHDATE')->value('19880101') }}

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