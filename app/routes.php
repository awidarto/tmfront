<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::controller('home', 'HomeController');
Route::controller('shop', 'ShopController');
Route::controller('news', 'NewsController');
Route::controller('products', 'ProductsController');
Route::controller('ajax', 'AjaxController');
Route::controller('page', 'PageController');
Route::controller('post', 'PostController');
Route::controller('event', 'EventController');
Route::controller('press', 'PressController');


Route::get('/', 'HomeController@getIndex');

Route::get('hashme/{mypass}',function($mypass){

    print Hash::make($mypass);
});

Route::get('insta',function(){

    $user_id = 647213689;
    $instagramconfig = array(
        'client_id' => 'aa4f0e77c438445a9879040409fa542a',
        'client_secret'=> '4dd61f9ae62a49a5bcdfc03435a06a3e',
        'access_token' => 'secret'
    );

    $api = Instaphp\Instaphp::Instance(null, $instagramconfig);
    //var_dump($api); // Epic fail!

    $instagramedia = $api->Users->Recent($user_id);

    $instaimages = $instagramedia->data;

    $instaimage = $instaimages[0];

    print_r($instaimage->images->low_resolution->url);

    $instaimage = $instaimage->images->low_resolution->url;

});

Route::get('oauth',function(){
    Session::put(Config::get('instagram::session_name'), Instagram::getAccessToken(Input::get('code')));
});

Route::get('page/cat/{slug}','PageController@getCat');
Route::get('page/view/{slug}','PageController@getView');
Route::get('page','PageController@getIndex');

Route::get('projects/view/{slug}','ProjectsController@getView');
Route::get('projects/{slug}','ProjectsController@getIndex');
Route::get('page','PageController@getIndex');

Route::get('media',function(){
    $media = Product::all();

    print $media->toJson();

});

Route::get('testwit',function(){
    $toimoitwit = Twitter::getSearch(array('q'=>'toimoi','lang'=>'id','include_entities'=>1));
    print_r($toimoitwit);
});

Route::get('regeneratepic',function(){

    set_time_limit(0);

    $property = new Property();

    $props = $property->get();

    $seq = new Sequence();

    foreach($props as $p){

        $large_wm = public_path().'/wm/wm_lrg.png';
        $med_wm = public_path().'/wm/wm_med.png';
        $sm_wm = public_path().'/wm/wm_sm.png';

        $files = $p->files;

        foreach($files as $folder=>$files){

            $dir = public_path().'/storage/media/'.$folder;

            if (is_dir($dir) && file_exists($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if($file != '.' && $file != '..'){
                            if(!preg_match('/^lrg_|med_|th_|full_/', $file)){
                                echo $dir.'/'.$file."\n";

                                $destinationPath = $dir;
                                $filename = $file;

                                $thumbnail = Image::make($destinationPath.'/'.$filename)
                                    ->fit(100,74)
                                    //->insert($sm_wm,0,0, 'bottom-right')
                                    ->save($destinationPath.'/th_'.$filename);

                                $medium = Image::make($destinationPath.'/'.$filename)
                                    ->fit(320,240)
                                    //->insert($med_wm,0,0, 'bottom-right')
                                    ->save($destinationPath.'/med_'.$filename);

                                $large = Image::make($destinationPath.'/'.$filename)
                                    ->fit(800,600)
                                    ->insert($large_wm,15,15, 'bottom-right')
                                    ->save($destinationPath.'/lrg_'.$filename);

                                $full = Image::make($destinationPath.'/'.$filename)
                                    ->insert($large_wm,15,15, 'bottom-right')
                                    ->save($destinationPath.'/full_'.$filename);

                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }


    }

});


Route::get('contact',function(){
    return View::make('pages.contact');
});

Route::get('login',function(){
    return View::make('login');
});

Route::post('login',function(){

    // validate the info, create rules for the inputs
    $rules = array(
        'email'    => 'required|email',
        'password' => 'required|alphaNum|min:3'
    );

    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);

    // if the validator fails, redirect back to the form
    if ($validator->fails()) {
        return Redirect::to('login')->withErrors($validator);
    } else {

        $userfield = Config::get('kickstart.user_field');
        $passwordfield = Config::get('kickstart.password_field');

        // find the user
        $user = User::where($userfield, '=', Input::get('email'))->first();


        // check if user exists
        if ($user) {

            // check if password is correct
            if (Hash::check(Input::get('password'), $user->{$passwordfield} )) {

                // login the user
                Auth::login($user);

                return Redirect::to('/');

            } else {
                // validation not successful
                // send back to form with errors
                // send back to form with old input, but not the password
                return Redirect::to('/')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            }

        } else {
            // user does not exist in database
            // return them to login with message
            Session::flash('loginError', 'This user does not exist.');
            return Redirect::to('login');
        }

    }

});

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('/');
});

/* Filters */

Route::filter('auth', function()
{

    if (Auth::guest()){
        Session::put('redirect',URL::full());
        return Redirect::to('login');
    }

    if($redirect = Session::get('redirect')){
        Session::forget('redirect');
        return Redirect::to($redirect);
    }

    //if (Auth::guest()) return Redirect::to('login');
});
