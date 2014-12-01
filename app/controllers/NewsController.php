<?php

class NewsController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        Breadcrumbs::setDivider('');
        Breadcrumbs::setCssClasses('breadcrumb');
        Breadcrumbs::addCrumb('Home',URL::to('/'));
    }

    public function getIndex()
    {

        Breadcrumbs::addCrumb('project',URL::to('shop/projects').'/'.$tag);
        Breadcrumbs::addCrumb($tag,URL::to('shop/projectds').'/'.$tag);
        return View::make('pages.news');
    }

    public function getDetail($id = null){
        return View::make('pages.newsreader');
    }

}