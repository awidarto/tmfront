<div class="slide-box" style="width:100%;">
    <h2>NEWS</h2>
    <ul id="slider1" class="slider">
        @foreach($news as $n)
            <li class="item">
                <div class="news-box">
                    <a href="{{ URL::to('post/view/'.$n['slug']) }}">
                        <h6>{{ $n['title'] }}</h6>
                        <p>
                            {{ $n['body'] }}
                        </p>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
</div>