@extends('layout.front')

@section('content')
<div id="home">
    <div class="row" id="item-picture">
        <div class="col-md-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="item-detail" style="width:600px;">
                <h2>Pallet Sofa</h2>
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
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>

    <div class="row" id="item-info" >
        <div class="col-md-8">

            <div class="col-md-6">
                <h2>Pallet Sofa</h2>
                buy now for IDR 2.350.000
                <div id="item-description">
                    <p>
                        the adaptable interior contains a varied family and personal collection of 2D pieces dating from the 1940′s to today, which was specifically measured for storing. they were organized and ordered according to groups, sizes and artistic connections. four separate proportions were allocated for, with each cell, cupboard or drawer crafted according to these requirements – only when the drawers are opened is the colorfully painted mosaic on the sides revealed
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <h2>Other Photos</h2>
                click to view the image
                <ul id="other-picture">
                    <li>
                        <img class="zoom-thumb" src="{{ URL::to('/') }}/images/dummy/detail_ot_1.jpg" data-disp="{{ URL::to('/') }}/images/dummy/detail_med_1.jpg" data-large="{{ URL::to('/') }}/images/dummy/detail_lrg_1.jpg" >
                    </li>
                    <li>
                        <img  class="zoom-thumb" src="{{ URL::to('/') }}/images/dummy/detail_ot_2.jpg" data-disp="{{ URL::to('/') }}/images/dummy/detail_med_2.jpg" data-large="{{ URL::to('/') }}/images/dummy/detail_lrg_2.jpg">
                    </li>
                </ul>
                <div class="clearfix"></div>
                <div id="buy-box">
                    {{ Former::framework('TwitterBootstrap')}}
                    {{ Former::open_vertical()}}
                    <?php
                        $qty = array(''=>'Select quantity');
                        for($i = 1;$i < 5; $i++){
                            $qty[$i] = $i;
                        }
                    ?>
                    {{ Former::select('qty','Quantity')->options($qty)->class('form-inline') }}
                    <br />
                    <a href="#" id="add-link" class="btn btn-primary">
                        Add to cart <b class="icon-cart"></b>
                    </a>
                    {{ Former::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4 tm-side">
            @include('partials.instagram')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
                <div class="detail-col" style="width:275px;">
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
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-col" style="width:275px;">
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
                    </ul>
                </div>
            </div>
        </div>



        <div class="col-md-4 tm-side">
            @include('partials.news')
        </div>
    </div>
</div>
@stop