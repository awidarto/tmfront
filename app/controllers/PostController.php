<?php

class PostController extends BaseController {

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
        $pages = Posts::get();
        return View::make('pages.pagelist')->with('pages',$pages);
    }

    public function getCat($slug = null)
    {

        if(is_null($slug)){

            $pages = Posts::get()->toArray();

        }else{
            $slug = ucfirst($slug);

            $pages = Posts::where('category','=',$slug)->get()->toArray();
        }

        return View::make('pages.pagelist')->with('pages',$pages);
    }

    public function getList($section,$category)
    {
        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');

        $pages = Posts::where('section','=',$section)
                    ->where('category','=',$category)
                    ->where('status','active')
                    ->skip($page * $perpage)
                    ->take($perpage)
                    ->get()->toArray();

        $currentcount = count($pages);

        $total_found = Posts::where('categoryLink',$category)
                        ->where('status','active')
                        ->count();

        $total_all = Posts::count();

        $paging = floor($total_found / $perpage);

        return View::make('pages.pagelist')
                    ->with('pages',$pages)
                    ->with('total',$total_found)
                    ->with('alltotal',$total_all)
                    ->with('current',$page)
                    ->with('perpage',$perpage)
                    ->with('currentcount',$currentcount)
                    ->with('paging',$paging);
    }


    public function __etList($section,$category)
    {
        /*
        if(!is_null($section) && !is_null($category) ){
            $pages = Posts::where('section','=',$section)->where('category','=',$category)->get()->toArray();
        }elseif(!is_null($section) && is_null($category) ){
            $pages = Posts::where('section','=',$section)->get()->toArray();
        }elseif(is_null($section) && !is_null($category) ){
            $pages = Posts::where('category','=',$category)->get()->toArray();
        }else{
            $pages = array();
        }
        */

        $pages = Posts::where('section','=',$section)->where('category','=',$category)->get()->toArray();

        return View::make('pages.pagelist')->with('pages',$pages);
    }


    public function getRead($section = null,$category = null, $slug = null){

        if(!is_null($section) && !is_null($category) && !is_null($slug)){
            $page = Posts::where('section','=',$section)
                ->where('category','=',$category)
                ->where('slug','=',$slug)
                ->first()->toArray();
        }else{
            $page = null;
        }
        return View::make('pages.pagereader')->with('content',$page);
    }

    public function getView($slug = null){

        if(!is_null($slug)){
            $page = Posts::where('slug','=',$slug)
                ->first()->toArray();
        }else{
            $page = null;
        }
        return View::make('pages.pagereader')->with('content',$page);
    }

    public function missingMethod($parameter = array()){

    }

}