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
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            <h2>My Purchases</h2>
            <div style="display:block;font-size:12px;">
                {{ $itemtable }}
            </div>
        </div>
        <div class="col-md-4 visible-lg tm-side">
            @include('partials.identity')
            @include('partials.location')
            @include('partials.twitter')
        </div>
    </div>
</div>
@stop