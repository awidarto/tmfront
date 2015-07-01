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
Route::controller('search', 'SearchController');
Route::controller('location', 'LocationController');

Route::controller('doku', 'DokuController');


Route::get('under',function(){
    return View::make('pages.under');
});

Route::group( array('domain'=>'toimoi.co.id'), function(){
    Route::get('/',function(){
        return View::make('pages.under');
    });
});
/*
Route::group( array('domain'=>'{sub}.toimoi.co.id'), function(){
    Route::get('/',function(){
        return View::make('pages.under');
    });
});
*/

Route::get('/', 'HomeController@getIndex');

Route::get('hashme/{mypass}',function($mypass){

    print Hash::make($mypass);
});

Route::get('testmail',function(){
    $data['email'] = 'andy.awidarto@gmail.com';
    $data['name'] = 'Andy Awidarto';
    $data['subject'] = 'Emailer Test';
    $template = 'emails.confirmation';

    Emailer::sendnotification($data, $template);

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
    $toimoitwit = Twitter::getSearch(array('q'=>'toimoi','include_entities'=>1));
    print_r($toimoitwit);
    $toimoitwit = Twitter::getSearch(array('q'=>'@toimoiindonesia','lang'=>'id','include_entities'=>1));
    print_r($toimoitwit);
    $toimoitwit = Twitter::getSearch(array('q'=>'#toimoiindonesia','lang'=>'id','include_entities'=>1));
    print_r($toimoitwit);
});

Route::get('sqpic',function(){
    $dir = public_path().'/storage/media/test.jpg';

    $sq = Image::make($dir)
        ->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

    $canvas = Image::canvas(310, 310, '#FFFFFF');

    return $canvas->insert($sq,'center')
        ->response('jpg');
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
            return Redirect::to('signup');
        }

    }

});

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('/');
});

Route::get('signup',function(){
    Former::framework('TwitterBootstrap3');
    return View::make('pages.register')->with('title','Sign Up');
});

Route::post('signup',function(){
    // validate the info, create rules for the inputs
    $rules = array(
        'firstname'    => 'required',
        'lastname'    => 'required',
        'email'    => 'required|email|unique:members',
        'pass' => 'required|alphaNum|min:3|same:repass'
    );

    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);

    // if the validator fails, redirect back to the form
    if ($validator->fails()) {

        Event::fire('log.a',array('create account','createaccount',Input::get('email'),'validation fail'));

        Session::flash('signupError', $validator->messages() );
        return Redirect::to('signup');
    } else {

        $data = Input::get();

        unset($data['csrf_token']);

        $model = new Buyer();

        $activation = str_random(15);

        $data['activation'] = $activation;
        $data['activeCart'] = '';

        $data['createdDate'] = new MongoDate();
        $data['lastUpdate'] = new MongoDate();

        unset($data['repass']);
        $data['pass'] = Hash::make($data['pass']);

        $data['fullname'] = $data['firstname'].' '.$data['lastname'];


        if($obj = $model->insert($data)){
            Event::fire('log.a',array('create account','createaccount',Input::get('email'),'account created'));
            //Event::fire('product.createformadmin',array($obj['_id'],$passwordRandom,$obj['conventionPaymentStatus']));
            //return Redirect::to('account/success');

            //$newuser = User::where('activation', $activation)->first()->toArray();
            $de['email'] = Input::get('email');
            $de['name'] = $data['fullname'];
            $de['subject'] = 'Welcome to Toimoi';
            Emailer::sendnotification($de, 'emails.signupsuccess');

            Session::flash('signupSuccess', 'Thank you and welcome to '.Config::get('site.name').' ! ');

            return Redirect::to('/');

        }else{

            Event::fire('log.a',array('create account','createaccount',Input::get('email'),'fail to create account'));

            $de['email'] = Input::get('email');
            $de['name'] = $data['fullname'];
            $de['subject'] = 'Sign up failed - Toimoi';
            Emailer::sendnotification($de, 'emails.signupfailed');

            //return Redirect::to($this->backlink)->with('notify_success',ucfirst(Str::singular($controller_name)).' saving failed');
            Session::flash('signupError', 'Failed to create membership');
            return Redirect::to('signup');
        }

    }


    return View::make('pages.createaccount');
});

Route::get('jne/origin',function(){
    $q = Input::get('term');

    $url = Laracurl::buildUrl(Config::get('jne.base_url').'origin/key/'.$q,[]);
    $resp = Laracurl::post($url, ['username' =>Config::get('jne.username') ,'api_key'=>Config::get('jne.key')]);

    $res = json_decode($resp->body);

    $result = array();

    foreach($res->detail as $r){
        $result[] = array('id'=>$r->code,'value'=>$r->code,'label'=>$r->label );
    }

    return Response::json($result);
});

Route::get('jne/dest',function(){

    $q = Input::get('term');

    $url = Laracurl::buildUrl(Config::get('jne.base_url').'dest/key/'.$q,[]);
    $resp = Laracurl::post($url, ['username' =>Config::get('jne.username') ,'api_key'=>Config::get('jne.key')]);

    $res = json_decode($resp->body);

    $result = array();

    foreach($res->detail as $r){
        $result[] = array('id'=>$r->code,'value'=>$r->code,'label'=>$r->label );
    }

    return Response::json($result);
});

Route::post('jne/price',function(){

    $from = Input::get('from');
    $thru = Input::get('dest');
    $weight = Input::get('from');

    $url = Laracurl::buildUrl(Config::get('jne.base_url').'price',[]);
    $resp = Laracurl::post($url, array(
            'username' =>Config::get('jne.username') ,
            'api_key'=>Config::get('jne.key'),
            'from'=>$from,
            'thru'=>$thru,
            'weight'=>$weight )
    );

    print_r($resp);

    //return Response::json( $resp );
    /*
    try{

    }catch(Exception $e){
        return Response::json( array('err'=>e ) );
    }
    */

});

Route::get('jne/price/{from}/{thru}/{weight}',function($from, $thru, $weight){

    $url = Laracurl::buildUrl(Config::get('jne.base_url').'price',[]);
    $from = Config::get('jne.default_origin');
    $resp = Laracurl::post($url, array(
            'username' =>Config::get('jne.username') ,
            'api_key'=>Config::get('jne.key'),
            'from'=>$from,
            'thru'=>$thru,
            'weight'=>$weight )
    );

    //print_r($resp->body);

    $response = json_decode($resp->body,true);

    //print_r($response);

    if(isset($response['price'])){
        foreach( $response['price'] as $p){
            $tar = new Jne();
            foreach($p as $k=>$v){
                $tar->{$k} = $v;
            }
            $tar->origin_code = $from;
            $tar->destination_code = $thru;
            $tar->createdDate = new MongoDate();
            $tar->save();
        }

        return Response::json( array('result'=>'OK', 'price'=>$response['price'] ) );

    }else{
        return Response::json( array('result'=>'ERR:PRICENOTFOUND', 'price'=>array() ) );
    }
    /*
    try{

    }catch(Exception $e){
        return Response::json( array('err'=>e ) );
    }
    */

});

/* Filters */

Route::filter('auth', function()
{

    if (Auth::guest()){
        Session::put('redirect',URL::full());
        return Redirect::to('/');
    }

    if($redirect = Session::get('redirect')){
        Session::forget('redirect');
        return Redirect::to($redirect);
    }

    //if (Auth::guest()) return Redirect::to('login');
});
