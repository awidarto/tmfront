@extends('layout.print')

@section('content')

            <h2>Purchase Receipt</h2>
            <div style="display:block;font-size:12px;">
                    {{ $itemtable }}
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
            </div>


@stop