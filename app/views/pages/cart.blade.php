@extends('layout.front')

@section('content')
<style type="text/css">
    td input.itemqty {
        margin-right:4px;
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
                @if($nocart)
                {{ $itemtable }}
                    <div class="row" >
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/collection')}}" class="btn btn-primary pull-left" id="to-cart">back to shop</a>
                        </div>
                        <div class="col-md-4 center">
                            <a href="{{ URL::to('shop/cancel')}}" class="btn btn-danger pull-right" id="cancel">cancel purchase</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::to('shop/methods')}}" class="btn btn-primary pull-right" id="check-out">process cart</a>
                        </div>
                    </div>
                @else
                    <p>
                        You have no active shopping cart, would you like to <a href="{{ URL::to('/') }}" alt="shop home">look around the shop ?</a>
                    </p>
                @endif
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
        $('.update-qty').on('click',function(){
            var item = $(this).prev('input');
            console.log(item.val());
            $.post('{{ URL::to('shop/changeqty')}}',
            {
                sku : item.data('sku'),
                prevqty : item.data('preval'),
                qty : item.val(),
                session : '{{ Auth::user()->activeCart }}'
            },function(data){
                if(data.result == 'OK:SUB'){
                    console.log(data.affected);
                    window.location = '{{ URL::to('shop/cart')}}';
                }else if(data.result == 'OK:ADD'){
                    console.log(data.affected.item_count);
                    window.location = '{{ URL::to('shop/cart')}}';
                }else{
                    alert('No changes made to cart');
                }
            },'json');
        });
    });
</script>
@stop