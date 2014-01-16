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
                @for($i = 0; $i < 4;$i++)
                    <li class="item-col">
                        <div class="item">
                            <a href="{{ URL::to('news/detail')}}">
                                <h1>What is it now</h1>
                                <p>
                                    The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform
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