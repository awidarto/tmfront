@extends('layout.front')


@section('content')

    {{ HTML::style('css/blueimp-gallery.min.css') }}

<style type="text/css">
    ul.years{
        list-style-type: none;
    }

    ul.thumbgrid{
        list-style-type: none;
    }

    ul.thumbgrid li{
        float: left;
        padding: 4px;
    }

    .gridcontainer{
        display: block;
        clear: both;
    }
</style>
<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>{{ $title }}</h2>
            @foreach($pages as $page)
                <div id="{{ $page['title'] }}" class="gridcontainer">
                    <h6>{{ $page['title'] }}</h6>
                    @if(isset($page['files']) && count($page['files']) > 0 )
                    <ul class="thumbgrid">
                        @foreach($page['files'] as $f)
                            <li>
                                <a href="{{ $f['full_url']}}" title="{{ $f['caption'] }}" data-gallery="#blueimp-gallery-{{ $page['slug']}}">
                                    <img src="{{ $f['thumbnail_url']}}" alt="" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            @endforeach

            <div class="pagination pull-right col-md-4">
                <ul>
                    <?php
                        $prev = ($current - 1 < 0 )?0:($current - 1);
                        $next = ($current + 1 > $paging )?$current:($current + 1);
                    ?>
                    <li class="" >
                        <a href="{{ mg(array('page'=>$prev))}}" class="prev" >
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                    <?php
                        $max_count = Config::get('shop.pagination_max_count');

                    ?>
                    @if( $max_count >= $paging )
                        @for($p = 0;$p < $paging + 1;$p++)
                            <li class="{{ ms('page',$p , 0) }}" >
                                <a href="{{ mg(array('page'=>$p))}}" >
                                        {{$p + 1}}
                                </a>
                            </li>
                        @endfor
                    @elseif( $max_count < $paging )

                        @if( $current >= ($max_count - 1) )
                            <?php
                                $pstart = $current - ($max_count - 2);
                                $pend = $pstart + $max_count;
                            ?>
                        @else
                            <?php
                                $pstart = 0;
                                $pend = $max_count;
                            ?>
                        @endif

                        @for($p = $pstart;$p < $pend;$p++)
                            <li class="{{ ms('page',$p , 0) }}" >
                                <a href="{{ mg(array('page'=>$p))}}" >
                                        {{$p + 1}}
                                </a>
                            </li>
                        @endfor

                    @endif
                    <li class="" >
                        <a href="{{ mg(array('page'=>$next))}}" class="next" >
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>

                </ul>
            </div>

        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

    {{ HTML::script('js/blueimp-gallery.min.js') }}
    {{ HTML::script('js/jquery.blueimp-gallery.min.js') }}

@stop