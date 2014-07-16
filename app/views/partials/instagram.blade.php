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

    $instaimages = $instagramedia->data;

    $instaimage = $instaimages[0];

    //print_r($instaimage->images->low_resolution->url);

    $image_url = $instaimage->images->low_resolution->url;

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