<?php

class ShopController extends BaseController {

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
        return View::make('pages.home');
    }

    public function getDetail($id = null){

        $product = Product::find($id);

        return View::make('pages.detail')->with('product',$product);
    }

    public function getCollection($category = null){

        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');

        //$categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );

        $products = Product::where('categoryLink',$category)
                        ->where('status','active')
                        ->skip($page * $perpage)
                        ->take($perpage)
                        ->get()->toArray();

        $currentcount = count($products);

        $total_found = Product::where('categoryLink',$category)
                        ->where('status','active')
                        ->count();

        $total_all = Product::count();

        $paging = floor($total_found / $perpage);

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

}