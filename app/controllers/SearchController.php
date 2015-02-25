<?php

class SearchController extends BaseController {

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->beforeFilter('auth', array('on'=>'get', 'only'=>array('getCart','getMethods','getReview','getCommit') ));

        Breadcrumbs::setDivider('');
        Breadcrumbs::setCssClasses('breadcrumb');
        Breadcrumbs::addCrumb('Home',URL::to('/'));

    }


    public function getCollection()
    {
        $search = Input::get('q');
        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');

        $categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );

        $keyword = new MongoRegex('/'.$search.'/i');

        $query = array('status'=>'active','colorVariantParent'=>'yes', '$or'=>array(
            array('itemDescription'=>$keyword),
            array('SKU'=>$keyword)
         ));

        $products = Product::whereRaw($query)
                        ->orderBy('itemDescription','asc')
                        ->orderBy('colour','asc')
                        ->skip($page * $perpage)
                        ->take($perpage)
                        ->get()->toArray();

        $currentcount = count($products);

        $total_found = Product::whereRaw($query)
                        ->count();

        $total_all = Product::count();

        $paging = floor($total_found / $perpage);

        Breadcrumbs::addCrumb('Search Result',URL::to('search/collection'));

        return View::make('pages.collection')
            ->with('products',$products)
            ->with('total',$total_found)
            ->with('alltotal',$total_all)
            ->with('current',$page)
            ->with('perpage',$perpage)
            ->with('currentcount',$currentcount)
            ->with('paging',$paging)
            ->with('category','All')
            ->with('colname','All');


    }

    public function postIndex()
    {

    }


}
