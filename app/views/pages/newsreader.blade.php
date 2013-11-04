@extends('layout.front')

@section('content')
<div id="home">
    <div class="row">
        <div class="col-md-8" id="detail-view">
            <div class="row visible-xs">
                <div class="col-md-12">
                    @include('partials.identity')
                </div>
            </div>
            <div class="row" id="newslistbox">
                <div class="col-md-12">
                    <h2>WHAT IS IT</h2>
                    <p>
The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To
                    </p>
                    <p>
The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To
                    </p>
                </div>
            </div>
            <div class="row">
                <h3>MORE NEWS ON THE SUBJECT</h3>
                <ul id="more-news">
                    @for($i = 0;$i < 5;$i++)
                    <li><a href="{{ URL::to('news/detail')}}">More News On the Subject</a></li>
                    @endfor
                </ul>
            </div>
        </div>
        <div class="col-md-4 visible-lg">
            <div class="row">
                @include('partials.identity')
                @include('partials.location')
            </div>
            <div class="row">
                @include('partials.news')
            </div>
            <div class="row">
                @include('partials.twitter')
                @include('partials.ad')
            </div>
         </div>
    </div>
</div>
@stop