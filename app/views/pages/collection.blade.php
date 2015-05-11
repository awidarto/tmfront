@extends('layout.front')

@section('content')
<?php

    if(Input::get('order') == 'asc'){
        $ord = 'desc';
    }else{
        $ord = 'asc';
    }
?>

<div id="home">
    <div class="row">
        <div class="col-md-12" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>

            {{ Breadcrumbs::render() }}
            @if(count($products) > 0 )
                <div id="col-list" class="item-col">
                    @for($i = 0; $i < count($products);$i++)
                            <div class="item">
                                <a href="{{ URL::to('shop/detail/'.$products[$i]['_id'])}}">
                                    <div class="photo">
                                        @if(isset($products[$i]['defaultpictures']['thumbnail_url'])
                                            && $products[$i]['defaultpictures']['thumbnail_url'] != ''
                                            )
                                            <img src="{{ $products[$i]['defaultpictures']['thumbnail_url'] }}" class="img-responsive" >
                                        @else
                                            <img src="{{ URL::to('/') }}/images/th_default.png" class="img-responsive" >
                                        @endif
                                    </div>
                                    <div class="description text-center">
                                        <h1>{{ $products[$i]['itemDescription']}}</h1>
                                        <p>
                                            IDR {{ Ks::idr($products[$i]['priceRegular']) }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                    @endfor
                </div>
            @else
                <p>No Product found in this category</p>
            @endif

                <div class="row" style="border:none;text-align:center;">
                    {{--
                    <div class="pull-right"  >
                        <p>
                            Items {{ ($current * $perpage) + 1 }} to {{ ( $current * $perpage ) + $currentcount }} of {{$total}} total (Filtered from {{$alltotal}} entries)
                        </p>
                    </div>
                    --}}
                        <ul class="pagination text-center" >
                            <?php
                                $prev = ($current - 1 < 0 )?0:($current - 1);
                                $next = ($current + 1 > $paging )?$current:($current + 1);
                            ?>
                            <li class="" >
                                <a href="{{ mg(array('page'=>$prev))}}" class="prev" >
                                    <i class="fa fa-chevron-left"></i>
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
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </li>

                        </ul>

                    <div style="text-align:center;display:block;">
                    </div>
                </div>

        </div>
    </div>
</div>
@stop