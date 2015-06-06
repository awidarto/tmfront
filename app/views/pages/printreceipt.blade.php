@extends('layout.print')

@section('content')

            <style type="text/css">
                h1{
                    display: block;
                    text-align: center;
                    font-size: 2em;
                }
            </style>

            <h1>Purchase Receipt</h1>
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
                    @else
                    <div class="row">
                        <div class="col-md-12 center">
                            <h6>This purchase is cancelled</h6>
                        </div>
                    </div>

                    @endif
            </div>


@stop