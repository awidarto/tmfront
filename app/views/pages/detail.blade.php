@extends('layout.front')

@section('content')
{{-- print_r($product)--}}
{{ Breadcrumbs::render() }}
<div id="home">
    <div class="row" id="item-picture" style="border-bottom:none;">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="item-detail" style="width:600px;max-width:600px;">
                @if(isset($product['defaultpictures']['large_url']))
                <div id="viewer">
                    <img id="zoomed" style="width:600px;max-width:600px;" src="{{ $product['defaultpictures']['full_url'] }}" data-zoom-image="{{ $product['defaultpictures']['full_url'] }}" />
                </div>
                hover to zoom
                @else
                    <img src="{{ URL::to('/') }}/images/lrg_default.png" />
                @endif
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#zoomed').imagezoomsl({
                        innerzoom:true,
                    });

                    $('.zoom-thumb').on('click',function(){
                        var that = this;
                        $('#zoomed').fadeOut(600,function(){
                            $(this).attr('src', $(that).data('disp'))
                                .data('large', $(that).data('large'))
                                .fadeIn(1000);
                        });
                    });

                });

            </script>

        </div>

        <div class="col-sm-4 col-md-4 col-lg-4 visible-lg visible-md visible-sm  tm-side item-detail">

                <h2 style="display:block;padding-left:0px;font-size:24px;font-weight:bold;margin-top:24px;">{{ $product['itemDescription']}}</h2>
                buy now for IDR {{ Ks::idr($product['priceRegular']) }}
                <div id="item-description">
                    <p>
                        {{ $product['itemDescription'] }}
                    </p>
                    <p>
                        Color :
                        <div class="color-chip-square" style="background-color:{{ Prefs::getColorCode($product['colour']) }}"></div>

                    </p>
                    <p>
                        Dimension : {{ $product['W'].' cm x '.$product['H'].' cm x '.$product['L'].' cm' }}
                    </p>
                    <p>
                        Diameter : {{ ($product['D'] == '')?'-': $product['D'] }} cm
                    </p>
                    <p>
                        Material : {{ ($product['material'] == '')?'-': $product['material']}}
                    </p>
                    <p>
                        <?php
                            if(isset($product['ShipmentQty']) && $product['ShipmentQty'] > 1){
                                $sunit = $product['ShipmentQty'].' units';
                            }else{
                                $sunit = ' units';
                            }
                        ?>
                        Shipping Weight : {{ $product['Weight'] }} kg / {{ $sunit }}
                    </p>
                </div>
                <style type="text/css">
                    ul.color-list{
                        list-style-type: none;
                        margin-left: 0px;
                        padding-left: 0px;
                        height: 50px;
                        display: block;
                    }

                    ul.color-list li{
                        float:left;
                    }

                    ul.color-list li a{
                        display: block;
                        margin-right: 8px;
                    }

                    ul.color-list li a div.color-chip{
                        display:block;
                        width:25px;
                        height:25px;
                    }

                    .color-box{
                        display: block;
                        padding: 10px;
                        padding-left:0px;
                        min-height: 50px;
                        height: 50px;

                    }

                    .buy-box{
                        display: block;
                        border: thin solid #eee;
                        margin-top: 20px;
                        padding: 15px;
                        font-weight: bold;
                        font-size: 12px;
                    }

                    .zoom-thumb{
                        border: thin solid #eee;
                        cursor: pointer;
                        width: 80px;
                    }

                    #viewer img{
                        border: thin solid #eee;
                    }

                    .buy-box .xlabel{
                        width: 120px;
                        color: black;
                        display: inline-block;
                        line-height: 25px;
                        margin-bottom: 10px;
                    }

                    #variant-box{
                        display: block;
                    }

                    .color-thumb{
                        width: 65px;
                        margin-left:0px !important;
                    }

                    #variant-box a{
                        padding: 6px;
                        display: block;
                        float: left;
                    }

                    .color-chip{
                        min-height: 25px;
                        height: 25px;
                        display: block;
                        margin-bottom: 2px;
                    }

                    .color-chip-square{
                        min-height: 35px;
                        height: 35px;
                        min-width: 35px;
                        width: 35px;
                        display: block;
                        margin-bottom: 2px;
                        border: thin solid #eee;
                    }

                </style>
                {{--
                <div class="color-box">
                    Available Colors
                    <ul class="color-list">
                        <li>
                            <a href="#">
                                <div class="color-chip" style="background-color:red;"></div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="color-chip" style="background-color:blue;"></div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="color-chip" style="background-color:green;"></div>
                            </a>
                        </li>
                    </ul>
                </div>
                --}}
                <div class="buy-box form-inline">
                    @if( Commerce::getAvailableCount($product['SKU'],Config::get('site.outlet_id'))->availableToCount() > 0)
                        <span class="xlabel">Select Quantity</span><br />
                        <span id="qty-select">
                        {{ Commerce::getAvailableCount($product['SKU'],Config::get('site.outlet_id'))->availableToSelection()->availableSelectionToHtml('quantity','') }}
                        </span>
                        <span class="btn btn-primary btn-sm" id="add-to-cart" ><i class="fa fa-shopping-cart fa-lg"></i> Add to Cart</span>
                    @else
                        <h4>{{ Config::get('shop.none_available')}}</h4>
                    @endif
                </div>
                @if(Auth::check())
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#add-to-cart').on('click',function(){

                                var sku = '{{ $product['SKU'] }}';
                                var qty = $('#quantity').val();
                                var cart = '{{ Auth::user()->activeCart }}';

                                $.post('{{ URL::to('shop/addtocart' ) }}',
                                    {
                                        sku : sku,
                                        qty : qty,
                                        cart : cart
                                    },
                                    function(data){
                                        if(data.result == 'OK'){
                                            $('span#cart-qty').html(data.total_count);
                                            alert( 'item added to cart');
                                            $('select#quantity').html( updateselector(data.available_count) );
                                        }else{
                                            alert( 'Failed adding to cart')
                                        }
                                    },'json'
                                );

                            });

                            function updateselector(count){
                                var opt = '';
                                for(var i = 1; i <= count;i++){
                                    opt += '<option value="' + i + '">' + i +'</option>';
                                }
                                return opt;
                            }
                        });


                    </script>
                @else

                @endif

                @if(count($colors) > 0)
                <div id="variant-box">
                    <h6>Available In</h6>
                    <div class="clearfix"></div>
                    <b id="colour-display">{{ $colors[0]->colour }}</b>
                    <div class="clearfix"></div>
                    @foreach ($colors as $c)
                        <?php $c = $c->toArray(); ?>
                        <a href="{{ URL::to('shop/detail/'.$c['_id'])}}">
                            @if(isset($c['defaultpictures']['medium_url']) && $c['defaultpictures']['medium_url'] != '')
                                <div>
                                    <div data-color="{{ ucwords($c['colour']) }}" class="color-chip-square color-thumb" style="background-color:{{ Prefs::getColorCode($c['colour']) }}"></div>
                                    {{--
                                    <img data-color="{{ $c['colour']}}" src="{{ $c['defaultpictures']['medium_url'] }}" class="color-thumb img-responsive" >
                                    --}}
                                </div>
                            @else
                                <div>
                                    <div data-color="{{ ucwords($c['colour']) }}" class="color-chip-square color-thumb" style="background-color:{{ Prefs::getColorCode($c['colour']) }}"></div>
                                    {{--
                                    <img data-color="{{ $c['colour']}}" src="{{ URL::to('/') }}/images/th_default.png" class="color-thumb img-responsive" >
                                    --}}
                                </div>
                            @endif
                        </a>
                    @endforeach
                    <div class="clearfix"></div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function(){
                        $('.color-thumb').on('mouseover',function(){
                            var color = $(this).data('color');

                            $('#colour-display').html(color);
                        })
                    });

                </script>

                @endif
        @if($_SERVER['HTTP_HOST'] != 'localhost')
            <div style="padding:10px 0;">
            <iframe src="//www.facebook.com/plugins/like.php?href={{ urlencode( URL::full() ) }}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font=verdana&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
            <br />
            <a href="https://twitter.com/share" class="twitter-share-button" data-via="toimoi" data-hashtags="toimoiindonesia">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

            </div>
        @endif

            {{--
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
            --}}
        </div>
    </div>

    <div class="row" id="item-info">
        <div class="col-md-8">
            @if(isset($product['defaultpictures']['large_url']))

            <h2 style="display:inline-block;">Other Photos</h2>
            ( click to view the image )
            <ul id="other-picture">
                @foreach($product['files'] as $pic)
                    <li>
                        <img class="zoom-thumb" src="{{ $pic['thumbnail_url']}}" data-disp="{{ $pic['large_url']}}" data-large="{{ $pic['full_url'] }}" >
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="col-md-4 tm-side">
            {{--
            @include('partials.instagram')
            --}}
        </div>
    </div>

    <style type="text/css">
        .item-col li .item{
            display: inline-block;
            float:left;
            margin: 0px;
            margin-right: 25px;
        }

        .img-responsive{
            display:block;
            margin-left: 25px;
        }
    </style>
    <div class="row" style="border-bottom:none;">
            <div class="col-md-6">
                <h2>RELATED ITEMS</h2>
                <ul id="related" class="item-col">
                        @if(isset( $product['relatedProducts'] ))
                            @foreach(Commerce::splitProductTag($product['relatedProducts']) as $prod )
                                <li>
                                    <div class="item">
                                        <a href="{{ URL::to('shop/detail/'.$prod['_id'])}}">
                                            <h1>{{ $prod['itemDescription']}}</h1>
                                            @if(isset($prod['defaultpictures']['medium_url'])
                                                && $prod['defaultpictures']['medium_url'] != ''
                                                )
                                                <img src="{{ $prod['defaultpictures']['medium_url'] }}" class="img-responsive" >
                                            @else
                                                <img src="{{ URL::to('/') }}/images/th_default.png" class="img-responsive" >
                                            @endif
                                            buy now for IDR {{ Ks::idr($prod['priceRegular']) }}
                                        </a>

                                    </div>
                                </li>
                            @endforeach
                        @endif
                </ul>
            </div>
            <div class="col-md-6">
                    <h2>RECOMMENDED</h2>
                    <ul id="recommended" class="item-col">
                        @if(isset( $product['recommendedProducts'] ))
                            @foreach(Commerce::splitProductTag($product['recommendedProducts']) as $prod )
                                <li>
                                    <div class="item">
                                        <a href="{{ URL::to('shop/detail/'.$prod['_id'])}}">
                                            <h1>{{ $prod['itemDescription']}}</h1>
                                            @if(isset($prod['defaultpictures']['medium_url'])
                                                && $prod['defaultpictures']['medium_url'] != ''
                                                )
                                                <img src="{{ $prod['defaultpictures']['medium_url'] }}" class="img-responsive" >
                                            @else
                                                <img src="{{ URL::to('/') }}/images/th_default.png" class="img-responsive" >
                                            @endif
                                            buy now for IDR {{ Ks::idr($prod['priceRegular']) }}
                                        </a>

                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
            </div>

    </div>
</div>
@stop