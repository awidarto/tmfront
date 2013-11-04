@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <div class="col-md-6">
                <h2>PRACTICAL DESIGN</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/5.jpg">
                    <h1>Sewing Table</h1>
                    <a href="{{ URL::to('shop/detail')}}">buy now for IDR 2.350.000</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2>LIFESTYLE</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/4.jpg">
                    <h1>A1 B1 Soft Baskets</h1>
                    <a href="{{ URL::to('shop/detail')}}">buy now for IDR 300.000</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 visible-lg">
            @include('partials.identity')
            @include('partials.location')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h2>INTERIOR DESIGN</h2>
            <img src="{{ URL::to('/') }}/images/dummy/6.jpg" class="img-responsive" >
        </div>
        <div class="col-md-4">
            @include('partials.news')
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-6">
                <h2>COMFORT ABOVE ALL</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/1.jpg">
                    <h1>Vespa Throw Pillows</h1>
                    <a href="">buy now for IDR 250.000</a>
                </div>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/2.jpg">
                    <h1>Book Stacks Throw Pillows</h1>
                    <a href="">buy now for IDR 350.000</a>
                </div>
            </div>
            <div class="col-md-6">
                <h2>FUNCTIONAL & UNIQUE</h2>
                <div class="item">
                    <img src="{{ URL::to('/') }}/images/dummy/3.jpg">
                    <h1>Elephant Shape Bookshelf</h1>
                    <a href="">buy now for IDR 450.000</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('partials.twitter')
            @include('partials.ad')
        </div>
    </div>
</div>
@stop