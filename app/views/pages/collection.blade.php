@extends('layout.front')

@section('content')
<?php

    function mg($newparam){

        $pstring = str_replace(URL::current(), '', URL::full());
        $pstring = str_replace('?', '', $pstring);

        parse_str($pstring,$reqs);

        $nreqs = array_merge($reqs,$newparam);
        $str = array();
        foreach ($nreqs as $k=>$v) {
            $str[]= $k.'='.$v;
        }
        $str = implode('&', $str);

        return URL::current().'?'.$str;
    }

    function ms($key, $val, $default){
        $pstring = str_replace(URL::current(), '', URL::full());
        $pstring = str_replace('?', '', $pstring);
        parse_str($pstring,$reqs);

        if(Input::get($key) == $val ){
            return 'active';
        }else if(Input::get($key) == '' && $val == $default){
            return 'active';
        }else{
            return '';
        }
    }

    function ps($page){

        if(Input::get('page') == $page ){
            return 'active';
        }else if(Input::get('page') == '' && $page == 0){
            return 'active';
        }else{
            return '';
        }

    }

    if(Input::get('order') == 'asc'){
        $ord = 'desc';
    }else{
        $ord = 'asc';
    }
?>

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
                                <a href="{{ URL::to('shop/detail/'.$products[$i]['_id'])}}">
                                    <h1>{{ $products[$i]['itemDescription']}}</h1>
                                    @if(isset($products[$i]['defaultpictures']['medium_url'])
                                        && $products[$i]['defaultpictures']['medium_url'] != ''
                                        )
                                        <img src="{{ $products[$i]['defaultpictures']['medium_url'] }}" class="img-responsive" >
                                    @else
                                        <img src="{{ URL::to('/') }}/images/th_default.png" class="img-responsive" >
                                    @endif
                                    buy now for IDR {{ Ks::idr($products[$i]['priceRegular']) }}
                                </a>
                            </div>
                        </li>
                    @endfor

                    <div class="pagination pagination-centered span4" style="color:#fff;width:320px;text-align:center">
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


                    <div class="span2 pull-right white-text" style="width:110px;" >
                        Items {{ ($current * $perpage) + 1 }} to {{ ( $current * $perpage ) + $currentcount }} of {{$total}}{{-- total (Filtered from {{$alltotal}} entries) --}}
                    </div>

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