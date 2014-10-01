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

    public function getDetail($id = null)
    {

        $product = Product::find($id);

        return View::make('pages.detail')->with('product',$product);
    }

    public function getCollection($category = null)
    {

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

    public function postAddtocart()
    {
        if(isset(Auth::user()->activeCart) && Auth::user()->activeCart != '' ){
            $cart = Auth::user()->activeCart;
        }else{
            $cart = str_random(5);
            $user = Buyer::find(Auth::user()->_id);
            $user->activeCart = $cart;
            $user->save();
        }

        $in = Input::get();

        $sku = $in['sku'];
        $qty = $in['qty'];
        $session = $cart;
        $outlet_id = Config::get('site.outlet_id');

        $result = Commerce::addToCart($sku, $qty, $session, $outlet_id);

        $selector = Commerce::getAvailableCount($sku,Config::get('site.outlet_id'))
            ->availableToSelection()->availableSelectionToHtml('quantity','');

        $result['available_count'] = Commerce::getAvailableCount($sku,Config::get('site.outlet_id'))->availableToCount();

        return Response::json($result);

    }

    public function getCart()
    {
        $itemtable = '';
        $session_id = Auth::user()->activeCart;
        $trx = Transaction::where('sessionId',$session_id)->get()->toArray();
        $pay = Payment::where('sessionId',$session_id)->get()->toArray();

        $tab = array();
        foreach($trx as $t){

            $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
            $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
            $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];

        }

        $tab_data = array();
        $gt = 0;
        foreach($tab as $k=>$v){
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>Former::text('itemqty[]','')->value($v['qty'])->class('itemqty col-md-4 input-sm')->id('')->data_sku($k).' <button class="btn btn-primary btn-sm" id="update-qty">update qty</button>', 'attr'=>'class="center"'),
                    array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                    array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                );
            $gt += $v['total'];
        }

        $tab_data[] = array('','','',array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.cart')->with('itemtable',$itemtable);
    }

    public function postCart()
    {

    }

    public function getMethods()
    {
        $itemtable = '';
        $session_id = Auth::user()->activeCart;
        $trx = Transaction::where('sessionId',$session_id)->get()->toArray();
        $pay = Payment::where('sessionId',$session_id)->get()->toArray();

        $tab = array();
        foreach($trx as $t){

            $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
            $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
            $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];

        }

        $tab_data = array();
        $gt = 0;
        foreach($tab as $k=>$v){
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>$v['qty'], 'attr'=>'class="center"'),
                    array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                    array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                );
            $gt += $v['total'];
        }

        $tab_data[] = array('','','',array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.method')->with('itemtable',$itemtable);

    }

    public function getReview()
    {

    }

    public function getCommit()
    {

    }

    public function postCommit()
    {

    }

    public function postChangeqty()
    {

    }
}