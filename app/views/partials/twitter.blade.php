<h2>@TOIMOITWIT</h2>
<div id="twitbox" class="lionbar">
    <?php

        $toimoitwit = null;

        try{
            $toimoitwit = Twitter::getSearch(array('q'=>'toimoi','lang'=>'id','include_entities'=>1));
        }catch(Exception $e){

        }
        //print_r($toimoitwit);
        //$toimoitwit = Twitter::getUserTimeline(array('screen_name'=>'toimoiindonesia'));

    ?>
    <ul>
        @if(!is_null($toimoitwit))
            @foreach($toimoitwit->statuses as $twit)
            <li>
                <h6>{{ '@'.$twit->user->screen_name }}</h6>
                <p>
                    {{ Twitter::linkify($twit->text) }}
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