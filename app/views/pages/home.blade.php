@extends('layout.front')

@section('content')


<div id="home">
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="slide-box" style="width:600px;">
                <ul id="slider1" class="slider">
                    @foreach($headslider as $hs)
                        <li>
                            @if(isset($hs['linkTo']) && $hs['linkTo'] != '')
                                <a href="{{ URL::to($hs['linkTo'])}}" >
                                    <img src="{{ $hs['defaultpictures']['full_url'] }}" />
                                </a>
                            @else
                                <img src="{{ $hs['defaultpictures']['full_url'] }}" />
                            @endif
                        </li>
                    @endforeach
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
                    @foreach($hello as $hs)
                        <li>
                            <div class="item">
                                <img src="{{ $hs['defaultpictures']['medium_url']}}">
                                <h1>{{ $hs['itemDescription']}}</h1>
                                <a href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">buy now for IDR {{ Ks::idr($hs['priceRegular']) }} <b class="icon icon-shopping-cart"></b></a>
                            </div>
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="col-md-6">
                <a href="#"  data-toggle="tooltip" title="Best Seller" ><h2>GOOD BUY</h2></a>
                <ul class="slider">
                    @foreach($goodbuy as $hs)
                        <li>
                            <div class="item">
                                <img src="{{ $hs['defaultpictures']['medium_url']}}">
                                <h1>{{ $hs['itemDescription']}}</h1>
                                <a href="{{ URL::to('shop/detail') }}/{{ $hs['_id']}}">buy now for IDR {{ Ks::idr($hs['priceRegular']) }} <b class="icon icon-shopping-cart"></b></a>
                            </div>
                        </li>
                    @endforeach
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
                        @foreach($idea as $hs)
                            <li>
                                <a href="{{ URL::to('/post/view/'.$hs['slug'])}}" >
                                    <img src="{{ $hs['defaultpictures']['medium_portrait_url'] }}" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="slide-box" style="width:275px;">
                    <h2>WE LOVE</h2>
                    <ul id="slider1" class="slider">
                        @foreach($welove as $hs)
                            <li>
                                <a href="{{ URL::to('/post/view/'.$hs['slug'])}}" >
                                    <img src="{{ $hs['defaultpictures']['medium_portrait_url'] }}" />
                                </a>
                            </li>
                        @endforeach
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