<?php

class PageController extends BaseController {

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

    public function getIndex()
    {
        $pages = Page::get();
        return View::make('pages.pagelist')->with('pages',$pages);
    }

    public function getCat($slug = null)
    {

        if(is_null($slug)){

            $pages = Page::get()->toArray();

        }else{
            $slug = ucfirst($slug);

            $pages = Page::where('category','=',$slug)->get()->toArray();
        }

        return View::make('pages.pagelist')->with('pages',$pages);
    }

    public function getList($section,$category)
    {
        /*
        if(!is_null($section) && !is_null($category) ){
            $pages = Page::where('section','=',$section)->where('category','=',$category)->get()->toArray();
        }elseif(!is_null($section) && is_null($category) ){
            $pages = Page::where('section','=',$section)->get()->toArray();
        }elseif(is_null($section) && !is_null($category) ){
            $pages = Page::where('category','=',$category)->get()->toArray();
        }else{
            $pages = array();
        }
        */

        $pages = Page::where('section','=',$section)->where('category','=',$category)->get()->toArray();

        return View::make('pages.pagelist')->with('pages',$pages);
    }


    public function getView($section = null,$category = null, $slug = null){

        if(!is_null($section) && !is_null($category) && !is_null($slug)){
            $page = Page::where('section','=',$section)
                ->where('category','=',$category)
                ->where('slug','=',$slug)
                ->first()->toArray();
        }else{
            $page = null;
        }
        return View::make('pages.pagereader')->with('content',$page);
    }

    public function missingMethod($parameter = array()){

    }

}