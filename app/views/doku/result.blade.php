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
            @if($in['status_code'] == '0000')
                <h2>Transaction Success</h2>
                <div class="container" style="display:block;font-size:12px;">
                    <p>
                        Thank you for your purchase, this is your Transaction Code :
                    </p>
                        <div style="font-size:24pt;font-weight:bold;display:block;text-align:center;padding:8px;">{{ $doku->cartId }}</div>
                    <p>
                        please keep it handy, you will need it to confirm your payment later.<br />
                        If you have made your transfer payment , you may confirm your payment here :<br />
                    </p>
                    <div class="clearfix center" style="text-align:center;">
                        <a href="{{ URL::to('shop/confirm')}}" class="btn btn-danger" id="cancel">Confirm Payment</a>
                    </div>

                    <p>
                        or call us and use this the transaction code.
                        Have a good day !
                    </p>
                </div>

            @else
                <h2>Transaction Canceled or Failed</h2>
                <div class="container" style="display:block;font-size:12px;">
                    <p>
                        We're sorry that your payment can not proceed, but no worry you can retry using other payment method or even using direct bank transfer to us.<br />
                        This is your Transaction Code :
                    </p>
                        <div style="font-size:24pt;font-weight:bold;display:block;text-align:center;padding:8px;">{{ $doku->cartId }}</div>
                    <p>
                        please keep it handy, you will need it to confirm your payment later.<br />
                        If you have made your transfer payment , you may confirm your payment here :<br />
                    </p>
                    <div class="clearfix center" style="text-align:center;">
                        <a href="{{ URL::to('shop/confirm')}}" class="btn btn-danger" id="cancel">Confirm Payment</a>
                    </div>
                    <p>
                        or call us and use this the transaction code.
                        Have a good day !
                    </p>
                </div>
            @endif
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
@stop