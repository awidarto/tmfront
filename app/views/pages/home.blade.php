@extends('layout.front')

@section('content')


<div id="home">
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="slide-box" style="width:600px;">
                <h2>PROJECT : INTERIOR</h2>
                <ul id="slider1" class="slider">
                  <li><img src="{{ URL::to('/') }}/images/dummy/slider1.jpg" /></li>
                  <li><img src="{{ URL::to('/') }}/images/dummy/slider2.jpg" /></li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="col-md-6">
                <a href="#"  data-toggle="tooltip" title="New Stuff" ><h2>HELLO</h2></a>
                <ul class="slider">
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/2.jpg">
                            <h1>Sewing Table</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/5.jpg">
                            <h1>Sewing Table</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/3.jpg">
                            <h1>Sewing Table</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                            <h1>Sewing Table</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/1.jpg">
                            <h1>Sewing Table</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <a href="#"  data-toggle="tooltip" title="Best Seller" ><h2>GOOD BUY</h2></a>
                <ul class="slider">
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                            <h1>A1 B1 Soft Baskets</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                            <h1>A1 B1 Soft Baskets</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/2.jpg">
                            <h1>A1 B1 Soft Baskets</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                    <li>
                        <div class="item">
                            <img src="{{ URL::to('/') }}/images/dummy/3.jpg">
                            <h1>A1 B1 Soft Baskets</h1>
                            <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000 <b class="icon icon-shopping-cart"></b></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 tm-side">
            @include('partials.instagram')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
                <div class="slide-box" style="width:275px;">
                    <h2>IDEAS</h2>
                    <ul id="slider1" class="slider">
                      <li><img src="{{ URL::to('/') }}/images/dummy/slide1.png" /></li>
                      <li><img src="{{ URL::to('/') }}/images/dummy/slide2.png" /></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="slide-box" style="width:275px;">
                    <h2>WE LOVE</h2>
                    <ul id="slider1" class="slider">
                      <li><img src="{{ URL::to('/') }}/images/dummy/slide2.png" /></li>
                      <li><img src="{{ URL::to('/') }}/images/dummy/slide1.png" /></li>
                    </ul>
                </div>
            </div>
        </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.slider').bxSlider({
                        mode: 'fade',
                        auto: true,
                        pager:false,
                        autoControls: false,
                        pause: 15000
                    });

                    $('[data-toggle="tooltip"]').tooltip({
                        placement:'right'
                    });

                });

            </script>


        <div class="col-md-4 tm-side">
            @include('partials.news')
        </div>
    </div>
</div>
@stop