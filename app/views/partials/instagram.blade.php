<h2>@TOIMOIGRAM</h2>

<?php
    $user_id = 647213689;
    $instagramconfig = array(
        'client_id' => 'aa4f0e77c438445a9879040409fa542a',
        'client_secret'=> '4dd61f9ae62a49a5bcdfc03435a06a3e',
        'access_token' => 'secret'
    );

    $api = Instaphp\Instaphp::Instance(null, $instagramconfig);
    //var_dump($api); // Epic fail!
    print_r($api->Users->Recent($user_id) );

    $obj = $api->Users->Recent($user_id);

    $instagramedia = $api->Users->Recent($user_id);

    $instaimages = $instagramedia->data;

    $instaimage = array_pop($instaimages);

    $instaimage = $instaimage->images->low_resolution->url;

?>

<div id="instabox" class="lionbar" >
    <img src="{{ $instaimage }}">
    </ul>
</div>