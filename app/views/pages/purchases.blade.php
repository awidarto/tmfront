@extends('layout.front')

@section('content')
<style type="text/css">
    label{
        display: block;
    }
    #main h5{
        display: block;
        background-color: transparent;
        color: #222;
    }

    button#print{
        text-transform: uppercase;
        color: #FFF;
        font-size: 12px;
    }

</style>
<div id="home">
    <div class="row">
        <div class="col-md-12" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>My Purchases</h2>
            <div style="display:block;font-size:12px;">
                @if($nopurchase)
                    {{ $itemtable }}
                @else
                    <p>You have no purchase before, would you like to <a href="{{ URL::to('/') }}" alt="shop home">look around the shop ?</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop