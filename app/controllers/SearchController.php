<?php

class SearchController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Product();

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
                                ->orWhere('SKU');
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
                                ->orWhere('SKU');
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
