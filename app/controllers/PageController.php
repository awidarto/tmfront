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
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        Breadcrumbs::setDivider('');
        Breadcrumbs::setCssClasses('breadcrumb');
        Breadcrumbs::addCrumb('Home',URL::to('/'));
    }

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
        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');


        $datemax = Page::where('section','=',$section)
                    ->where('category','=',$category)
                    ->where('status','active')
                    ->max('createdDate');

        $datemin = Page::where('section','=',$section)
                    ->where('category','=',$category)
                    ->where('status','active')
                    ->min('createdDate');

        $start    = (new DateTime( date('Y-m-d H:i:s',$datemin->sec ) ))->modify('first day of this month');
        $end      = (new DateTime( date('Y-m-d H:i:s',$datemax->sec ) ))->modify('first day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        $archives = array();
        foreach ($period as $dt) {
            $archives[$dt->format("Y-m")] = $dt->format("F Y");
        }

        krsort($archives);

        $pages = Page::where('section','=',$section)
                    ->where('category','=',$category)
                    ->where('status','active')
                    ->skip($page * $perpage)
                    ->take($perpage)
                    ->get()->toArray();

        $currentcount = count($pages);

        $total_found = Page::where('categoryLink',$category)
                        ->where('status','active')
                        ->count();

        $total_all = Page::count();

        $paging = floor($total_found / $perpage);

        Breadcrumbs::addCrumb($section,URL::to('shop/projects').'/'.$section);
        Breadcrumbs::addCrumb($category,URL::to('shop/projectds').'/'.$category);

        return View::make('pages.pagelist')
                    ->with('pages',$pages)
                    ->with('total',$total_found)
                    ->with('alltotal',$total_all)
                    ->with('current',$page)
                    ->with('perpage',$perpage)
                    ->with('currentcount',$currentcount)
                    ->with('archives',$archives)
                    ->with('paging',$paging);
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