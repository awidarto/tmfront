@extends('layout.front')


@section('content')

    {{ HTML::style('css/blueimp-gallery.min.css') }}
<img src="{{ URL::to('js/bxslider/images/bx_loader.gif')}}" class="versteck" style="display:none;">
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

    ul.thumbgrid img{
        width: 255px;
        height: auto;
    }
</style>
<div id="home">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>

            {{ Breadcrumbs::render() }}

            <h2>{{ $title }}</h2>
            <?php
                $page = $currentpage;
            ?>

            <div id="{{ $page['title'] }}" class="gridcontainer">
                <h6>{{ $page['title'] }}</h6>
                @if(isset($page['files']) && count($page['files']) > 0 )

                <ul class="slider">
                    @foreach($page['files'] as $f)
                        <li>
                            <a href="{{ $f['full_url']}}" title="{{ $f['caption'] }}" data-gallery="#blueimp-gallery-{{ $page['slug']}}">
                                <img src="{{ $f['full_url']}}" alt="" />
                            </a>
                        </li>
                    @endforeach
                </ul>
                @endif
            </div>


        </div>
        <div class="col-sm-4 col-md-4 col-lg-4 visible-lg visible-md visible-sm  tm-side">
            <h4>Archive</h4>
            <ul>
            @foreach($pages as $page)
                <li>
                    <a href="{{ URL::to('projects/'.$tag.'?s='.$page['slug']) }}">
                        <h6>{{ $page['title'] }}</h6>
                    </a>
                </li>
            @endforeach
            </ul>
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

    <script type="text/javascript">
        $(document).ready(function(){
            $('.slider').bxSlider({
                mode: 'fade',
                auto: true,
                pager:false,
                controls:true,
                autoControls: false,
                pause: 15000
            });

            $('[data-toggle="tooltip"]').tooltip({
                placement:'right'
            });

        });

    </script>


    {{ HTML::script('js/blueimp-gallery.min.js') }}
    {{ HTML::script('js/jquery.blueimp-gallery.min.js') }}

@stop