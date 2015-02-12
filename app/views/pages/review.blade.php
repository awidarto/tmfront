@extends('layout.front')

@section('content')
<style type="text/css">
    label{
        display: block;
    }
    #main h5{
        display: block;
        background-color: transparent;
        color: #222;
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
            <div style="display:block;font-size:12px;">
                <form id="paymethod" action="{{ URL::to('shop/review')}}" method="post" >
                    {{ $itemtable }}
                    {{ Former::hidden('status','final') }}
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Pay using</h6>
                            <h5>{{ strtoupper($payment['payment_method'])  }}</h5>
                            {{ Former::hidden('payment',$payment['payment_method'])}}
                        </div>
                        <div class="col-md-6">
                            <h6>Deliver using</h6>
                            <h5>{{ strtoupper($payment['delivery_method'])  }}</h5>
                            {{ Former::hidden('delivery',$payment['delivery_method'])}}
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/cart')}}" class="btn btn-primary pull-left" id="to-cart">back to cart</a>
                        </div>
                        <div class="col-md-4 center">
                            <a href="{{ URL::to('shop/cancel')}}" class="btn btn-danger pull-right" id="cancel">cancel purchase</a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary pull-right" id="submit">check out</a>
                        </div>
                    </div>
                </form>
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