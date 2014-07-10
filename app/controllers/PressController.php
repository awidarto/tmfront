<?php

class PressController extends BaseController {

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

        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');

        $pages = Showcase::where('tags','like','%press%')
                    ->skip($page * $perpage)
                    ->take($perpage)
                    ->orderBy('title','desc')
                    ->get()->toArray();

        $currentcount = count($pages);

        $total_found = Showcase::where('tags','like','%press%')->count();

        $total_all = Showcase::count();

        $paging = floor($total_found / $perpage);

        return View::make('pages.press')
            ->with('title','Press')
            ->with('pages',$pages)
            ->with('total',$total_found)
            ->with('alltotal',$total_all)
            ->with('current',$page)
            ->with('perpage',$perpage)
            ->with('currentcount',$currentcount)
            ->with('paging',$paging);
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


    public function getView($slug = null){

        if(!is_null($slug)){
            $page = Page::where('slug','=',$slug)
                ->first()->toArray();
        }else{
            $page = null;
        }
        return View::make('pages.pagereader')->with('content',$page);
    }

    public function missingMethod($parameter = array()){

    }

}