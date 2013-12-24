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
        <div class="col-md-4 visible-lg">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="col-md-6">
                <h2>HELLO</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/5.jpg">
                    <h1>Sewing Table</h1>
                    <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2>GOOD BUY</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                    <h1>A1 B1 Soft Baskets</h1>
                    <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('partials.news')
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
                        autoControls: true,
                        pause: 2000
                    });
                });

            </script>


        <div class="col-md-4">
            @include('partials.ad')
        </div>
    </div>
</div>
@stop