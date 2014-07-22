<h2>@TOIMOIGRAM</h2>

<?php
    $user_id = Config::get('instagram.user_id');
    $instagramconfig = array(
        'client_id' => Config::get('instagram.client_id'),
        'client_secret'=> Config::get('instagram.client_secret'),
        'access_token' => Config::get('instagram.access_token')
    );

    $api = Instaphp\Instaphp::Instance(null, $instagramconfig);
    //var_dump($api); // Epic fail!
    //print_r($api->Users->Recent($user_id) );

    $obj = $api->Users->Recent($user_id);

    $instagramedia = $api->Users->Recent($user_id);

    print_r($instagramedia);

    die();

    if(!is_null($instagramedia)){
        $instaimages = $instagramedia->data;

        $instaimage = $instaimages[0];

        $image_url = $instaimage->images->low_resolution->url;
    }else{
        $image_url = URL::to('images/th_default.png');
    }

?>
<style type="text/css">
    #instabox img{
        width:200px;
        height:auto;
    }
</style>
<div id="instabox" class="lionbar" >
    <img src="{{ $image_url }}">
    </ul>
</div>