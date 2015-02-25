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
                {{ Former::open_vertical('shop/methods')->id('paymethod')}}
                    {{ $itemtable }}
                    {{ Former::hidden('status','review') }}
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Deliver to</h5>

                            {{ Former::text('recipientname','To')->class('form-control')->value(Auth::user()->fullname) }}

                            {{ Former::textarea('shipping_address', 'Shipping Address')->class('form-control')->value(Auth::user()->address) }}
                            {{ Former::text('city','City')->class('form-control')->value(Auth::user()->city) }}
                            {{ Former::text('countryOfOrigin','Country')->class('form-control')->value(Auth::user()->countryOfOrigin) }}
                            {{ Former::text('phone','Phone')->class('form-control')->value(Auth::user()->phone) }}


                            <h5>Deliver using</h5>
                            @if( $warn = Session::get('methodFail'))
                                <p class="bg-danger bold">
                                    {{ $warn }}
                                </p>
                            @endif
                            <div class="clearfix"></div>
                            <h6>JNE</h6>
                            <div class="form-inline">

                            <div class="row" style="border:none;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    {{ Former::text('jne_dest','Destination')->class('form-control jne_dest_auto')->id('jne-dest')->help('type in destination city (autocomplete)')}}
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    {{ Former::text('jne_weight','Weight')->class('form-control jne_weight_auto')
                                        ->value($weight)->id('jne-weight')}}
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <button style="margin-top: 20px;" class="btn btn-primary" id="jne-get-tariff">Get Tariff</button>
                                </div>
                            </div>
                            <div class="row" style="border:none;">
                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                    {{ Former::select('jne_tariff','Tariff')->class('form-control jne_tariff')
                                        ->options(array(''=>'Specify Origin , Destination & Weight then click Get Tariff'))->id('jne-tariff')}}
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <a href="http://wwww.jne.co.id" target="_blank" >go to jne.co.id <i class="fa fa-external-link-square"></i></a>
                                </div>
                            </div>

                            {{ Former::hidden('jne_origin','Origin')->class('form-control jne_origin_auto col-md-4')->id('jne-origin')->value(Config::get('jne.default_origin'))}}


                            <span id="loading-indicator" style="display:none;">Loading...</span>

                            </div>
                        </div>
                    </div>
                    {{--

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Pay using</h6>
                            <label class="form-control" >
                                <input type="radio" name="payment" selected="selected" value="transfer" /> Bank Transfer
                            </label>
                            <label class="form-control" >
                                <input type="radio" name="payment" value="cc" /> Credit Card
                            </label>
                        </div>
                    </div>

                    --}}
                    <div class="row" >
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/cart')}}" class="btn btn-primary pull-left" id="to-cart">back to cart</a>
                        </div>
                        <div class="col-md-4 center">
                            <a href="{{ URL::to('shop/cancel')}}" class="btn btn-danger pull-right" id="cancel">cancel purchase</a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary pull-right" id="submit">proceed to payment</button>
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
        $('#jne-origin').autocomplete({
            source:'{{ URL::to('jne/origin') }}'
        });

        $('#jne-dest').autocomplete({
            source:'{{ URL::to('jne/dest') }}'
        });

        $('#jne-get-tariff').on('click',function(){
            var origin = $('#jne-origin').val();
            var dest = $('#jne-dest').val();
            var weight = $('#jne-weight').val();

            $('#loading-indicator').show();
            $.get('{{ URL::to('jne/price') }}/' + origin +'/'+ dest +'/'+ weight,
                function(data){
                    if(data.result == 'OK'){
                        $('#jne-tariff').html('');
                        $('#jne-tariff').append('<option value="">Select available tariff</option>');
                        for (var i = data.price.length - 1; i >= 0; i--) {
                            var d = data.price[i];
                            var val = d.price;
                            var label = d.service_display + ' : IDR ' + d.price
                            $('#jne-tariff').append('<option value="'+ val +'">' + label+ '</option>');
                        };

                        $('#loading-indicator').hide();

                    }else{
                        $('#loading-indicator').hide();
                    }
                },'json');
            return false;
        });

        $('#jne-tariff').on('change',function(){
            var sel = this.value;
            var sub = $('#sub-total').val();

            $('#delivery-charge').val(sel);
            $('#total-charge').val(parseInt(sel) + parseInt(sub));

            var total = accounting.formatNumber(parseInt(sel) + parseInt(sub), 2, ".", ",");
            $('#total-cost').html( total );

            sel = accounting.formatNumber(parseInt(sel), 2, ".", ",");
            $('#delivery-cost').html(sel );
        });

    });
</script>
@stop