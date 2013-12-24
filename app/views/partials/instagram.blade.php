<h2>@TOIMOIGRAM</h2>
<div id="instabox" class="lionbar" >
    <ul>
        @for($i = 0; $i < 10;$i++)
        <li>
            <img src="{{ URL::to('/')}}/images/dummy/insta1.png">
        </li>
        <li>
            <img src="{{ URL::to('/')}}/images/dummy/insta2.png">
        </li>
        @endfor
    </ul>
</div>