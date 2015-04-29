@extends('layout.front')

@section('content')

<style type="text/css">
    .slide-box-container{
        width: 215px;
        max-width: 230px;
        height: 215px;
        max-height: 230px;
        overflow: hidden;
        border: solid thin #ccc;
        position: relative;
    }

    .slide-box-container a.title{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
    }

    .three-box-slide{
        width: 280px;
        max-width: 280px;
        height: 215px;
        max-height: 230px;
        overflow: hidden;
        border: solid thin #ccc;
        position: relative;
    }

    .three-box-slide a.title{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
    }
</style>

<div id="home">
    <div class="row">
        <div class="col-md-9">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="slide-box" style="width:100%;">
                <ul id="slider1" class="slider">
                    @foreach($headslider as $hs)
                        @if(isset($hs['defaultpictures']['full_url']) )
                        <li>
                            @if(isset($hs['linkTo']) && $hs['linkTo'] != '')
                                <a href="{{ URL::to($hs['linkTo'])}}" >
                                    <img src="{{ $hs['defaultpictures']['full_url'] }}" />
                                </a>
                            @else
                                <img src="{{ $hs['defaultpictures']['full_url'] }}" />
                            @endif
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-3 visible-lg">
            <div class="slide-box-container">
                <a href="#" class="title" data-toggle="tooltip" title="New Stuff" ><h2>HELLO</h2></a>
                <ul class="slider">
                    @foreach($hello as $hs)
                        <li>
                            <div class="item home">
                                @if(isset($hs['defaultpictures']['medium_url']))
                                    <a class="slide" href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">
                                    <img src="{{ $hs['defaultpictures']['medium_url']}}">
                                    </a>
                                @endif
                                {{--
                                <h1>{{ $hs['itemDescription']}}</h1>
                                <a class="slide" href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">buy now for<br />IDR {{ Ks::idr($hs['priceRegular']) }} <b class="fa fa-shopping-cart"></b></a>
                                --}}
                            </div>
                        </li>
                    @endforeach

                </ul>
            </div>
            <br />
            <div class="slide-box-container">
                <a href="#"  class="title"  data-toggle="tooltip" title="Best Seller" ><h2>GOOD BUY</h2></a>
                <ul class="slider">
                    @foreach($goodbuy as $hs)
                        <li>
                            <div class="item home">
                                @if(isset($hs['defaultpictures']['medium_url']))
                                    <a class="slide" href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">
                                        <img src="{{ $hs['defaultpictures']['medium_url']}}">
                                    </a>
                                @endif
                                {{--
                                <h1>{{ $hs['itemDescription']}}</h1>
                                <a class="slide" href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">buy now for<br /><span class="strike">IDR {{ Ks::idr($hs['priceRegular']) }}</span> <span class="discount">IDR {{ (isset($hs['priceDiscount']))?Ks::idr($hs['priceDiscount']):'' }}</span> <b class="fa fa-shopping-cart"></b></a>
                                --}}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4">
            <div class="three-box-slide">
                <div class="slide-box" style="width:100%;">
                    <h2>WE LOVE</h2>
                    <ul id="slider1" class="slider">
                        @foreach($welove as $hs)
                            @if(isset($hs['defaultpictures']['medium_portrait_url']))
                            <li>
                                <a href="{{ URL::to('/post/view/'.$hs['slug'])}}" >
                                    <img src="{{ $hs['defaultpictures']['medium_portrait_url'] }}" />
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="three-box-slide">
                <div class="slide-box" style="width:100%;">
                    <h2>IDEAS</h2>
                    <ul id="slider1" class="slider">
                        @foreach($idea as $hs)
                            @if(isset($hs['defaultpictures']['medium_portrait_url']))
                            <li>
                                <a href="{{ URL::to('/post/view/'.$hs['slug'])}}" >
                                    <img src="{{ $hs['defaultpictures']['medium_portrait_url'] }}" />
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="three-box-slide">
                <div class="slide-box" style="width:100%;">
                    <h2>QUOTE</h2>
                    <ul id="slider1" class="slider">
                        @foreach($quotes as $hs)
                            @if(isset($hs['defaultpictures']['medium_portrait_url']))
                            <li>
                                <a href="{{ URL::to('/post/view/'.$hs['slug'])}}" >
                                    <img src="{{ $hs['defaultpictures']['medium_portrait_url'] }}" />
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="col-md-6">

            </div>
            <div class="col-md-6">
            </div>
        </div>
        <div class="col-md-4 tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
            @include('partials.instagram')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
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