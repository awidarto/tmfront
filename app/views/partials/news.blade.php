<h2>NEWS</h2>
<div id="newsbox" class="lionbar">
    <ul>
        @foreach($news as $n)
            <li class="item">
                <h6>{{ $n['title'] }}</h6>
                <p>
                    {{ $n['body'] }}
                </p>
                <a href="{{ URL::to('post/view/'.$n['slug']) }}">more &raquo;</a>
            </li>
        @endforeach
    </ul>
</div>