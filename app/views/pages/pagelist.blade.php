@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="newslistbox">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
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
                @for($i = 0; $i < count($pages);$i++)
                    <li class="item-col">
                        <div class="item">
                            <a href="{{ URL::to('page/view/'.$pages[$i]['slug'])}}">
                                <h1>{{ $pages[$i]['title'] }}</h1>
                                <p>
                                    {{ str_limit(strip_tags($pages[$i]['body']), 150, '...')  }}
                                </p>
                                <span>more &raquo;</span>
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
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
@stop