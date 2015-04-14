@extends('layout.front')

@section('content')

<div id="home">
    <div class="row">
        <div class="col-md-8" id="main">
            <div class="col-md-4 visible-xs">
                @include('partials.identity')
            </div>
            {{ Breadcrumbs::render() }}
            @if(is_null($content))
                <h2>Page Doesn't Exists</h2>
            @else
                <h2>{{ $content['title'] }}</h2>
            @endif
            <div style="display:block;font-size:12px;">
                @if(is_null($content))
                    <p>Sorry, this page apparently does not exist yet.</p>
                @else
                    {{ $content['body'] }}
                @endif
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