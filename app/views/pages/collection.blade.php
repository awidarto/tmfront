@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>

            <ul id="col-list" class="item-col">

                @if(count($products) > 0 )
                    @for($i = 0; $i < count($products);$i++)
                        <li>
                            <div class="item">
                                <a href="{{ URL::to('shop/detail/'.$product['_id'])}}">
                                    <h1>{{ $product['title']}}</h1>
                                    buy now for IDR 2.350.000
                                    <img src="{{ URL::to('/') }}/images/dummy/5.jpg" class="img-responsive" >
                                </a>
                            </div>
                        </li>
                    @endfor
                @else
                    <p>No Product found in this category</p>
                @endif
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