<h2>@TOIMOITWIT</h2>
<div id="twitbox" class="lionbar">
    <?php

    //$toimoitwit = Twitter::getSearch(array('q'=>'toimoi'));

    $toimoitwit = Twitter::getUserTimeline(array('screen_name'=>'toimoiindonesia'));

    ?>
    <ul>
        @if(!is_null($toimoitwit) && is_array($toimoitwit))
            @foreach($toimoitwit as $twit)
            <li>
                <h6>{{ '@'.$twit->user->screen_name }}</h6>
                <p>
                    {{ makeUrl($twit->text) }}
                </p>
            </li>
            @endforeach
        @endif
    </ul>
</div>

<?php
    function makeUrl($text){
        $text = explode(' ',$text);
        $new = array();
        foreach($text as $w){
            if(preg_match('/^http/', $w)){
                $w = '<a href="'.$w.'">'.$w.'</a>';
            }

            $new[] = $w;
        }

        return implode(' ',$new);
    }

?>