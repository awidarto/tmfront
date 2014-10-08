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
            <div style="display:block;font-size:12px;">
                <form id="paymethod" action="{{ URL::to('shop/review')}}" method="post" >
                    {{ $itemtable }}
                    {{ Former::hidden('status','review') }}
                    <div class="row">
                        <div class="col-md-6 form-horizontal">
                            <h6>Deliver using</h6>
                            <label class="form-control" >
                                <input type="radio" name="delivery" selected="selected" value="jne" /> JNE
                            </label>
                            <label class="form-control" >
                                <input type="radio" name="delivery" value="cod" /> COD
                            </label>
                        </div>
                        <div class="col-md-6">
                            <h6>Pay using</h6>
                            <label class="form-control" >
                                <input type="radio" name="payment" selected="selected" value="transfer" /> Bank Transfer
                            </label>
                            <label class="form-control" >
                                <input type="radio" name="payment" value="cc" /> Credit Card
                            </label>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/cart')}}" class="btn btn-primary pull-left" id="to-cart"><i class="fa fa-arrow-left"></i> back to cart</a>
                        </div>
                        <div class="col-md-4 center">
                            <a href="{{ URL::to('shop/cancel')}}" class="btn btn-danger pull-right" id="cancel">cancel purchase</a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary pull-right" id="submit">review order</button>
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