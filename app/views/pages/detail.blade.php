@extends('layout.front')

@section('content')
<div id="home">
    <div class="row" id="item-picture" style="border-bottom:none;">
        <div class="col-md-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="item-detail" style="width:600px;">
                <div id="viewer">
                    <img id="zoomed"  src="{{ URL::to('/') }}/images/dummy/detail_med.jpg" data-zoom-image="{{ URL::to('/') }}/images/dummy/detail_lrg.jpg"/>
                </div>
                hover to zoom
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
        <div class="col-md-4 visible-lg tm-side item-detail">
                <h2 style="display:block;padding-left:0px;font-size:24px;font-weight:bold;">Pallet Sofa</h2>
                buy now for IDR 2.350.000
                <div id="item-description">
                    <p>
                        the adaptable interior contains a varied family and personal collection of 2D pieces dating from the 1940′s to today, which was specifically measured for storing. they were organized and ordered according to groups, sizes and artistic connections. four separate proportions were allocated for, with each cell, cupboard or drawer crafted according to these requirements – only when the drawers are opened is the colorfully painted mosaic on the sides revealed
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
                        border: thin solid #000;
                        border-radius: 8px;
                        margin-top: 20px;
                        padding: 15px;
                        font-weight: bold;
                        font-size: 12px;
                    }

                    .zoom-thumb{
                        border: thin solid black;
                        cursor: pointer;
                    }

                    #viewer img{
                        border: thin solid black;
                    }

                    .buy-box .xlabel{
                        width: 120px;
                        color: black;
                        display: inline-block;
                        line-height: 25px;
                        margin-bottom: 10px;
                    }

                </style>

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
                <div class="buy-box">
                    <span class="xlabel">Select Quantity</span>
                    <select>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select><br />
                    <span class="xlabel">Add to Cart</span> <i class="icon-shopping-cart icon-2x"></i>
                </div>

            {{--
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
            --}}
        </div>
    </div>

    <div class="row" id="item-info">
        <div class="col-md-8">
            <h2 style="display:inline-block;">Other Photos</h2>
            ( click to view the image )
            <ul id="other-picture">
                <li>
                    <img class="zoom-thumb" src="{{ URL::to('/') }}/images/dummy/detail_ot_1.jpg" data-disp="{{ URL::to('/') }}/images/dummy/detail_med_1.jpg" data-large="{{ URL::to('/') }}/images/dummy/detail_lrg_1.jpg" >
                </li>
                <li>
                    <img  class="zoom-thumb" src="{{ URL::to('/') }}/images/dummy/detail_ot_2.jpg" data-disp="{{ URL::to('/') }}/images/dummy/detail_med_2.jpg" data-large="{{ URL::to('/') }}/images/dummy/detail_lrg_2.jpg">
                </li>
            </ul>

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
                    <li>
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <h1>Film Counter Pillow</h1>
                                buy now for IDR 2.350.000

                            </a>
                            <img src="{{ URL::to('/') }}/images/dummy/detail_rel_1.jpg" class="img-responsive" >
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <h1>Cinema Ticket Pillow</h1>
                                buy now for IDR 350.000

                                <img src="{{ URL::to('/') }}/images/dummy/detail_rel_2.jpg" class="img-responsive" >
                            </a>
                        </div>
                    </li>

                    <li>
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <h1>Film Counter Pillow</h1>
                                buy now for IDR 2.350.000

                            </a>
                            <img src="{{ URL::to('/') }}/images/dummy/detail_rel_1.jpg" class="img-responsive" >
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <h1>Cinema Ticket Pillow</h1>
                                buy now for IDR 350.000

                                <img src="{{ URL::to('/') }}/images/dummy/detail_rel_2.jpg" class="img-responsive" >
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
            <div class="col-md-6">
                    <h2>RECOMMENDED</h2>
                    <ul id="recommended" class="item-col">
                        <li>
                            <div class="item">
                                <a href="{{ URL::to('shop/detail')}}">
                                    <h1>Film Dressing Table</h1>
                                    buy now for IDR 2.350.000

                                    <img src="{{ URL::to('/') }}/images/dummy/detail_rec_1.jpg" class="img-responsive" >
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <a href="{{ URL::to('shop/detail')}}">
                                    <h1>Film Side Table</h1>
                                    buy now for IDR 350.000

                                    <img src="{{ URL::to('/') }}/images/dummy/detail_rec_2.jpg" class="img-responsive" >
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <a href="{{ URL::to('shop/detail')}}">
                                    <h1>Film Dressing Table</h1>
                                    buy now for IDR 2.350.000

                                    <img src="{{ URL::to('/') }}/images/dummy/detail_rec_1.jpg" class="img-responsive" >
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <a href="{{ URL::to('shop/detail')}}">
                                    <h1>Film Side Table</h1>
                                    buy now for IDR 350.000

                                    <img src="{{ URL::to('/') }}/images/dummy/detail_rec_2.jpg" class="img-responsive" >
                                </a>
                            </div>
                        </li>
                    </ul>
            </div>

    </div>
</div>
@stop