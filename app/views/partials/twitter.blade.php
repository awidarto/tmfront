<h2>@TOIMOITWIT</h2>
<div id="twitbox" class="lionbar">
    <?php

    $toimoitwit = Twitter::getSearch(array('q'=>'toimoi'));

    ?>
    <ul>
        @foreach($toimoitwit->statuses as $twit)
        <li>
            <h6>{{ '@'.$twit->user->screen_name }}</h6>
            <p>
                {{ $twit->text }}
            </p>
        </li>
        @endforeach
    </ul>
</div>