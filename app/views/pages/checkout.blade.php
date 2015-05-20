@extends('layout.front')

@section('content')
<style type="text/css">
    label{
        display: block;
    }
</style>
<div id="home">
    <div class="row">
        <div class="col-md-12" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>Shopping Cart</h2>
            <div style="display:block;font-size:12px;">
                {{ $itemtable }}
                <div class="row">
                    <div class="col-md-6">
                        <h6>Pay using</h6>
                        <label>
                            <input type="radio" name="payment" selected="selected" value="transfer" /> Bank Transfer
                        </label>
                        <label>
                            <input type="radio" name="payment" value="cc" /> Credit Card
                        </label>
                    </div>
                    <div class="col-md-6">
                        <h6>Deliver using</h6>
                        <label>
                            <input type="radio" name="delivery" selected="selected" value="jne" /> JNE
                        </label>
                        <label>
                            <input type="radio" name="delivery" value="cod" /> COD
                        </label>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-4">
                        <button class="btn btn-primary pull-left" id="to-cart">back to cart</button>
                    </div>
                    <div class="col-md-4 center">
                        <button class="btn btn-danger pull-right" id="cancel">cancel purchase</button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary pull-right" id="submit">submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop