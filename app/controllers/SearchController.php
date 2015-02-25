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
        $products = Product::where(function($query){
                            $query->orWhere('itemDescription',$keyword)
                                ->orWhere('SKU',$keyword);
                        })
                        ->where('status','active')
                        ->where('colorVariantParent','yes')
                        ->orderBy('itemDescription','asc')
                        ->orderBy('colour','asc')
                        ->skip($page * $perpage)
                        ->take($perpage)
                        ->get()->toArray();

        $currentcount = count($products);

        $total_found = Product::where(function($query){
                            $query->orWhere('itemDescription',$keyword)
                                ->orWhere('SKU',$keyword);
                        })
                        ->where('status','active')
                        ->where('colorVariantParent','yes')
                        ->count();

        $total_all = Product::count();

        $paging = floor($total_found / $perpage);

        $categoryName = $categories[$category];

        Breadcrumbs::addCrumb($categoryName,URL::to('shop/collection').'/'.$category);

        return View::make('pages.collection')
            ->with('products',$products)
            ->with('total',$total_found)
            ->with('alltotal',$total_all)
            ->with('current',$page)
            ->with('perpage',$perpage)
            ->with('currentcount',$currentcount)
            ->with('paging',$paging)
            ->with('category',$category)
            ->with('colname',$category);


    }

    public function postIndex()
    {

    }


}
