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

    button#print{
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
            <h2>Purchase Receipt</h2>
            <div style="display:block;font-size:12px;">
                    {{ $itemtable }}
                    @if($payment['sessionStatus'] != 'canceled')

                    <div class="row">
                        <div class="col-md-4">
                            <h6>Pay using</h6>
                            <h5>{{ strtoupper($payment['payment_method'])  }}</h5>
                            {{ Former::hidden('payment',$payment['payment_method'])}}
                        </div>
                        <div class="col-md-4">
                            <h6>Deliver using</h6>
                            @if(isset($payment['delivery_method']))
                                <h5>{{ strtoupper($payment['delivery_method'])  }}</h5>
                                {{ Former::hidden('delivery',$payment['delivery_method'])}}
                            @else
                                <h5>JNE</h5>
                                {{ Former::hidden('delivery','JNE')}}
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6>Status</h6>
                            <h5>{{ strtoupper($payment['status'])  }}</h5>
                            {{ Former::hidden('status',$payment['status'])}}
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px;border:none">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            @if($payment['payment_method'] == 'transfer')

                                <p>
                                    Thank you for your purchase, this is your Transaction Code :
                                </p>
                                    <p style="font-size:18pt;font-weight:bold;display:block;text-align:center;padding:8px;">{{ $payment['sessionId'] }}</p>
                                <p>
                                    please keep it handy, you will need it to confirm your payment later.<br />
                                    If you have made your transfer payment , you may confirm your payment using our online payment confirmation or call us and use this the transaction code.<br />
                                    Have a good day !
                                </p>

                            @else

                            @endif
                        </div>
                    </div>

                    @endif
                    <div class="row" style="border:none" >
                        <div class="col-md-4">
                        <div class="col-md-4 center">
                            <a href="{{ URL::to('shop/purchases')}}" class="btn btn-primary pull-left" id="confirm">back to my purchases</a>
                        </div>
                        </div>
                        <div class="col-md-4 center">
                            @if($payment['sessionStatus'] != 'canceled')
                                <a href="{{ URL::to('shop/confirm')}}" class="btn btn-primary pull-left" id="confirm">confirm payment</a>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/print/'.$purchase_id)}}" target="_blank" class="btn btn-primary pull-right" id="print">print receipt</a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@stop