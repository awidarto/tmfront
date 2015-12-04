@extends('layout.front')

@section('content')

<style type="text/css">
    .slide-box-container{
        width: 100%;
        /*max-width: 230px;*/
        height: 200px;
        max-height: 230px;
        overflow: hidden;
        border: solid thin #eee;
        position: relative;
    }

    .slide-box-container a.title{
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 100;
    }

    .slide-box-container a.title h2{
        padding: 5px;
        margin: 0px;
        background-color: #000;
        color: #FFF;
    }

    .slide-box-container h2{
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 100;

        padding: 5px;
        margin: 0px;
        background-color: #000;
        color: #FFF;
    }

    .three-box-slide{
        width: 100%;
        /*
        max-width: 280px;
        */
        height: 215px;
        max-height: 230px;
        overflow: hidden;
        border: solid thin #eee;
        position: relative;
    }

    .three-box-slide h2{
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 100;
        margin: 0px;
        padding: 6px;
        background-color: #000;
        color: #FFF;
    }

    .home-foot h2{
        background-color: #000;
        padding: 2px 4px;
        color: #FFF;
    }

    .news-box{
        height: 230px;
        overflow: hidden;
        text-align: left;
    }

    .news-box h6{
        margin-top: 25px;
        background-color: white;
        color: black;
        font-weight: bold;
        display: block;
    }

    h1.separator{
        width: 100%;
        font-size: 20px;
        text-align: center;
        background-color: #cccc;
        margin:0px;
        padding: 15px;
    }
</style>

<div id="home">
    <div class="row" style="border:none;">
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
            {{--
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            --}}
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

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 visible-lg visible-md visible-sm visible-xs">
            <div class="slide-box-container">
                @include('partials.location')
            </div>
            <br />
            <div class="slide-box-container">
                @include('partials.instagram')
            </div>
        </div>
    </div>

    <div class="row" style="border:none;">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="three-box-slide">
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

                {{--
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
                --}}
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="three-box-slide">
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
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="three-box-slide">
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
                {{--
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
                --}}
            </div>
        </div>
    </div>

    <div class="row" style="border:none;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: #ccc;border:none;">
            <h1 class="separator" >PRODUCT</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" id="main">

            {{ Breadcrumbs::render() }}
            @if(count($products) > 0 )
                <div id="col-list" class="item-col">
                    @for($i = 0; $i < count($products);$i++)
                            <div class="item col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <a href="{{ URL::to('shop/detail/'.$products[$i]['_id'])}}">
                                    <div class="photo">
                                        @if(isset($products[$i]['defaultpictures']['thumbnail_url'])
                                            && $products[$i]['defaultpictures']['thumbnail_url'] != ''
                                            )
                                            <img src="{{ $products[$i]['defaultpictures']['thumbnail_url'] }}" class="img-responsive" >
                                        @else
                                            <img src="{{ URL::to('/') }}/images/th_default.png" class="img-responsive" >
                                        @endif
                                    </div>
                                    <div class="description text-center">
                                        <h1>{{ $products[$i]['itemDescription']}}</h1>
                                        <p>
                                            @if(Config::get('shop.display_with_ppn'))
                                                <b>IDR {{ Ks::idr($products[$i]['priceRegular'] + ($products[$i]['priceRegular'] * Config::get('shop.ppn') ) , 0  ) }}</b>
                                            @else
                                                <b>IDR {{ Ks::idr($products[$i]['priceRegular'], 0) }}</b>
                                            @endif

                                        </p>
                                    </div>
                                </a>
                            </div>
                    @endfor
                </div>
            @else
                <p>No Product found in this category</p>
            @endif


        </div>
    </div>

    {{--

    <div class="row home-foot" >
        <div class="col-md-3 col-sm-12 col-xs-12">

        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            @include('partials.twitter')
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">

        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            @include('partials.news')
        </div>
    </div>

    --}}
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.slider').bxSlider({
                        mode: 'fade',
                        auto: true,
                        pager:false,
                        controls:false,
                        autoControls: false,
                        pause: 15000
                    });

                    $('[data-toggle="tooltip"]').tooltip({
                        placement:'right'
                    });

                });

            </script>

</div>
@stop