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
        <div class="col-md-12" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>Shopping Cart</h2>
            <div class="container" style="display:block;font-size:12px;">
                    {{ $itemtable }}
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Delivered To</h5>
                            <div class="clearfix"></div>
                            <p>
                                {{ $pay['by_name']}}<br />
                                {{ $pay['by_address']}}<br />
                            </p>
                            <h6>JNE</h6>
                            <p>
                                Origin : {{ $pay['jne_origin'] }} <i class="fa fa-chevron-right"></i> Dest : {{$pay['jne_dest']}}<br />
                                Delivery Cost : IDR {{ Ks::idr($pay['jne_tariff']) }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>How Do You Want To Pay ?</h5>
                                <p>
                                    Make a bank transfer to this account, then notify us :
                                    BCA a/c : 286 301 3348
                                    a/n PT Toimoi Indonesia
                                    BCA Cab. Kemang
                                </p>
                            {{ Former::open_vertical('shop/paytransfer')->id('paymethod')}}
                            {{ Former::hidden('status','review') }}
                                <button class="btn btn-primary pull-right" id="submit">Pay Using Bank Transfer</button>
                            {{Former::close()}}

                            <div class="clearfix"></div>
                            <p>
                                Or, use Doku if you want to pay using credit cards, and many other options other than bank transfer
                            </p>
                            {{ Former::open_vertical('shop/paydoku')->id('paymethod')}}
                                {{ Former::hidden('status','review') }}
                                <button class="btn btn-primary pull-right" id="submit">Pay Using Doku</button>
                            {{Former::close()}}
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/cart')}}" class="btn btn-primary pull-left" id="to-cart">back to cart</a>
                        </div>
                        <div class="col-md-4 center">
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/cancel')}}" class="btn btn-danger pull-right" id="cancel">cancel purchase</a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){


    });
</script>
@stop