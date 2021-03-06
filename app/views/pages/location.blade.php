@extends('layout.front')


@section('content')
{{ HTML::script('js/gmap3/gmap3.js') }}

<style type="text/css">
    #map{
        width: 100%;
        height:600px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#map').gmap3({
                map:{
                    options:{
                        center:[-6.17742,106.828308],
                        zoom:11,
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                        },
                        navigationControl: true,
                        scrollwheel: true,
                        streetViewControl: true
                    }
                },
                marker:{
                    values: [
                        {{ implode(',',$markers) }}
                    ],
                    options: {
                        draggable: false
                    },
                    events:{
                        mouseover: function(marker, event, context){
                            var map = $(this).gmap3('get'),
                                infowindow = $(this).gmap3({get:{name:'infowindow'}});
                                if (infowindow){
                                    infowindow.open(map, marker);
                                    infowindow.setContent(context.data);
                                } else {
                                    $(this).gmap3({
                                        infowindow:{
                                            anchor:marker,
                                            options:{content: context.data}
                                        }
                                    });
                                }
                        },
                        mouseout:function(){
                                    var infowindow = $(this).gmap3({get:{name:'infowindow'}});
                                    if (infowindow){
                                      infowindow.close();
                                    }
                                }
                    }
                }

            });
    });
</script>
<div id="home">
    <div class="row">
        <div class="col-md-12" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>{{ $title }}</h2>
            <div style="display:block;font-size:12px;">
                @if(!empty($body))
                    {{ $body['body'] }}
                @endif
                <div id="map">

                </div>
            </div>
        </div>
    </div>
</div>
@stop