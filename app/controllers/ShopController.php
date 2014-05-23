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
        return View::make('pages.detail');
    }

    public function getCollection($category = null,$page = 0){

        //$categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );

        $products = Product::where('category',$category)
                        ->get()->toArray();


        return View::make('pages.collection')
            ->with('products',$products)
            //->with('categories', $categories)
            ->with('colname',$category);
    }

}