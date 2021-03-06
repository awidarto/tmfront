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
            <h2>Bank Transfer Confirmation</h2>
            <div class="container" style="display:block;font-size:12px;">
                {{ Former::open_vertical('shop/confirm')->id('payconfirm')}}
                    <div class="row" style="border:none" >
                        <div class="col-md-12">

                            {{ Former::text('toimoicode','Toimoi Transaction Code')->class('form-control') }}

                            {{ Former::text('accountname','Account Name')->class('form-control')->value(Auth::user()->fullname) }}
                            {{ Former::text('bank','Bank Name')->class('form-control') }}
                            {{ Former::text('accountnumber','Account Number')->class('form-control') }}
                            {{ Former::text('transaction_code','Transfer Code')->class('form-control') }}

                            {{ Former::textarea('message', 'Message')->class('form-control') }}
                            {{ Former::text('transferamount','transfer Amount')->class('form-control') }}

                            {{ Former::text('phone','Phone')->class('form-control')->value(Auth::user()->phone) }}

                        </div>
                    </div>
                    <div class="row" style="border:none" >
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4 center">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary pull-right" id="submit">confirm</button>
                        </div>
                    </div>
                {{Former::close()}}
            </div>
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