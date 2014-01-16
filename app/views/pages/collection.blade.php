@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>

            <ul id="col-list">
                @for($i = 0; $i < 4;$i++)
                    <li class="item-col">
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <img src="{{ URL::to('/') }}/images/dummy/5.jpg" class="img-responsive" >
                                <h1>Sewing Table</h1>
                                buy now for IDR 2.350.000
                                 <b class="icon icon-shopping-cart"></b>
                            </a>
                        </div>
                    </li>
                    <li class="item-col">
                        <div class="item">
                            <a href="{{ URL::to('shop/detail')}}">
                                <img src="{{ URL::to('/') }}/images/dummy/4.jpg" class="img-responsive" >
                                <h1>A1 B1 Soft Baskets</h1>
                                buy now for IDR 350.000
                                 <b class="icon icon-shopping-cart"></b>
                            </a>
                        </div>
                    </li>
                @endfor
            </ul>

        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
@stop