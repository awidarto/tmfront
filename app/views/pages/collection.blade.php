@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="detail-view">
            <div class="row visible-xs">
                <div class="col-md-12">
                    @include('partials.identity')
                </div>
            </div>
            <div class="row" id="collectionbox">
                <div class="col-md-12">

                    <h2>{{$colname}}</h2>
                    <div class="clearfix"></div>
                    <div class="pagination pull-right">
                        <ul>
                            <li><a href="">&laquo;</a></li>
                            @for($i = 1;$i < 10;$i++)
                                <li><a href="">{{$i}}</a></li>
                            @endfor
                            <li><a href="">&raquo;</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <ul id="col-list">
                        @for($i = 0; $i < 4;$i++)
                            <li class="item-col">
                                <div class="item">
                                    <a href="{{ URL::to('shop/detail')}}">
                                        <img src="{{ URL::to('/') }}/images/dummy/5.jpg" class="img-responsive" >
                                        <h1>Sewing Table</h1>
                                        buy now for IDR 2.350.000
                                    </a>
                                </div>
                            </li>
                            <li class="item-col">
                                <div class="item">
                                    <a href="{{ URL::to('shop/detail')}}">
                                        <img src="{{ URL::to('/') }}/images/dummy/4.jpg" class="img-responsive" >
                                        <h1>A1 B1 Soft Baskets</h1>
                                        buy now for IDR 2.350.000
                                    </a>
                                </div>
                            </li>
                        @endfor
                    </ul>

                    <div class="clearfix"></div>
                    <div class="pagination pull-right">
                        <ul>
                            <li><a href="">&laquo;</a></li>
                            @for($i = 1;$i < 10;$i++)
                                <li><a href="">{{$i}}</a></li>
                            @endfor
                            <li><a href="">&raquo;</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 visible-lg">
            <div class="row">
                @include('partials.identity')
                @include('partials.location')
            </div>
            <div class="row">
                @include('partials.news')
            </div>
            <div class="row">
                @include('partials.twitter')
                @include('partials.ad')
            </div>
         </div>
    </div>
</div>
@stop