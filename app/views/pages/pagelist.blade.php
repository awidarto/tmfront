@extends('layout.front')

@section('content')
<style type="text/css">
    #newslistbox ul.breadcrumb{
        margin-left: 0px;
    }

</style>

<div id="home">
    <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" id="newslistbox">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            {{ Breadcrumbs::render() }}
            <ul id="col-list">
                @for($i = 0; $i < count($pages);$i++)
                    <li class="">
                        <div class="item">
                            <a href="{{ URL::to('page/view/'.$pages[$i]['slug'])}}">
                                <h1>{{ $pages[$i]['title'] }}</h1>
                                <p>
                                    {{ str_limit(strip_tags($pages[$i]['body']), 350, '...')  }}
                                </p>
                                <p>
                                <span>{{ date('d/m/Y', $pages[$i]['createdDate']->sec )  }}</span>
                                <span class="pull-right">more &raquo;</span>
                                </p>
                            </a>
                        </div>
                    </li>
                @endfor
            </ul>
                    <div class="clearfix"></div>
                <div class="row" style="border:none;">
                    {{--
                    <div class="pull-right"  >
                        Items {{ ($current * $perpage) + 1 }} to {{ ( $current * $perpage ) + $currentcount }} of {{$total}} total (Filtered from {{$alltotal}} entries)
                    </div>
                    --}}

                    <div class="pagination" style="margin-left:25px;">
                        <ul>
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
                    </div>
                </div>

        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 visible-lg visible-md visible-sm  tm-side">
            <h4>Archive</h4>
            <ul>
            @foreach($archives as $k=>$v)
                <li>
                    <a href="{{ mg(array('ar'=>$k)) }}">
                        <h6>{{ $v }}</h6>
                    </a>
                </li>
            @endforeach
            </ul>
        </div>

    </div>
</div>
@stop