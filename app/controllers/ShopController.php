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
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        $this->beforeFilter('auth', array('on'=>'get', 'only'=>array('getCart','getMethods','getReview','getCommit') ));
    }


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
            $itemform = Former::text('itemqty[]','')->value($v['qty'])->class('itemqty col-md-4 input-sm')->id('')->data_sku($k)->data_preval($v['qty']);
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>$itemform.' <button class="btn btn-primary btn-sm update-qty" >update qty</button>', 'attr'=>'class="center"'),
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

        $totalform = Former::hidden('totalprice',$gt);

        $tab_data[] = array('','',$totalform,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

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

    public function postReview()
    {
        $in = Input::get();

        $trx = Payment::where('sessionId', Auth::user()->activeCart )->first();

        if($trx){

        }else{
            $trx = new Payment();
            $trx->sessionId = Auth::user()->activeCart;
            $trx->createdDate = new MongoDate();
            $trx->sessionStatus = 'open';
        }

        if($in['status'] == 'final'){
            $trx->sessionStatus = 'final';
        }

        $trx->payment_method = $in['payment'];
        $trx->delivery_method = $in['delivery'];

        $trx->by_name = Auth::user()->fullname;
        $trx->by_address = Auth::user()->address;
        $trx->cc_amount = 0;
        $trx->cc_number = '';
        $trx->cc_expiry = '';
        $trx->dc_amount = 0;
        $trx->dc_number = '';
        $trx->payable_amount = $in['totalprice'];
        $trx->cash_amount = 0;
        $trx->cash_change = 0;
        $trx->lastUpdate = new MongoDate();

        $payment = $trx->toArray();

        $trx->save();

            if($in['status'] == 'final'){

                $itarray = array();
                $unitarr = array();

                $items = Transaction::where('sessionId',Auth::user()->activeCart )->get();
                $outlet_id = '';
                $outlet_name = '';
                foreach($items as $item){
                    //print_r($item->toArray());
                    $outletId = $item->outletId;
                    $outletName = $item->outletName;

                    $item->sessionStatus = 'final';

                    $unit = Stockunit::find($item->unitId);
                    //print_r($unit);
                    $unit->status = 'sold';
                    $unit->lastUpdate = new MongoDate();

                    $itarray[] = $item->toArray();
                    $unitarr[] = $unit->toArray();

                    $unit->save();
                    $item->save();
                }

                $sales = Sales::where('sessionId', Auth::user()->activeCart )->first();

                if($sales){

                }else{
                    $sales = new Sales();
                    $sales->sessionId = Auth::user()->activeCart;
                    $sales->createdDate = new MongoDate();
                }
                $sales->outletId = $outletId;
                $sales->outletName = $outletName;

                /*
                $sales->buyer_name = $in['by_name'];
                $sales->buyer_address = $in['by_address'];
                $sales->cc_amount = $in['cc_amount'];
                $sales->cc_number = $in['cc_number'];
                $sales->cc_expiry = $in['cc_expiry'];
                $sales->dc_amount = $in['dc_amount'];
                $sales->dc_number = $in['dc_number'];
                $sales->payable_amount = $in['payable_amount'];
                $sales->cash_amount = $in['cash_amount'];
                $sales->cash_change = $in['cash_change'];
                */

                $sales->buyer_id = Auth::user()->_id;
                $sales->buyer_name = Auth::user()->fullname;
                $sales->buyer_address = Auth::user()->address;
                $sales->buyer_city = Auth::user()->city;
                $sales->cc_amount = 0;
                $sales->cc_number = '';
                $sales->cc_expiry = '';
                $sales->dc_amount = 0;
                $sales->dc_number = '';
                $sales->payable_amount = $in['totalprice'];
                $sales->cash_amount = 0;
                $sales->cash_change = 0;
                $sales->lastUpdate = new MongoDate();

                $sales->transaction = $itarray;
                $sales->stockunit = $unitarr;
                $sales->payment = $payment;
                $sales->transactiontype = 'online';
                $sales->transactionstatus = 'checkout';
                $sales->save();
            }

        if($in['status'] == 'final'){
            return Redirect::to('shop/receipt');
        }else{
            return Redirect::to('shop/review');
        }

    }

    public function getReview()
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

        $totalform = Former::hidden('totalprice',$gt);

        $tab_data[] = array('','',$totalform,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.review')->with('itemtable',$itemtable)->with('payment',$pay[0]);

    }

    public function getReceipt($id = null)
    {
        $itemtable = '';

        if(is_null($id)){
            $session_id = Auth::user()->activeCart;
        }else{
            $session_id = $id;
        }
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

        $totalform = Former::hidden('totalprice',$gt);

        $tab_data[] = array('','',$totalform,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.receipt')->with('itemtable',$itemtable)->with('payment',$pay[0])
                    ->with('purchase_id',$session_id);

    }

    public function getPrint($id = null)
    {
        $itemtable = '';

        if(is_null($id)){
            $session_id = Auth::user()->activeCart;
        }else{
            $session_id = $id;
        }
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

        $totalform = Former::hidden('totalprice',$gt);

        $tab_data[] = array('','',$totalform,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.printreceipt')->with('itemtable',$itemtable)->with('payment',$pay[0]);

    }

    public function getPurchases()
    {
        $purchases = Sales::where('buyer_id',Auth::user()->_id)->where('transactiontype','online')->get();

        $itemtable = '';

        $header = array(
            'purchase date',
            'purchase id',
            'purchase value',
            'purchase status'
        );

        foreach($purchases->toArray() as $p){
            $tab_data[] = array($p['createdDate'],
                '<a href="'.URL::to('shop/receipt/'.$p['sessionId'] ).'">'.$p['sessionId'].'</a>',
                array('value'=>'IDR '.Ks::idr($p['payable_amount']), 'attr'=>'style="text-align:right;font-weight:bold;"'),
                $p['transactionstatus'] );
        }

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.purchases')->with('itemtable',$itemtable);
    }

    public function getOrders()
    {

    }

    public function postCommit()
    {

    }

    public function postChangeqty()
    {
        $in = Input::get();

        $prev = $in['prevqty'];
        $new = $in['qty'];
        $sku = $in['sku'];
        $sessionId = $in['session'];
        $outlet_id = Config::get('site.outlet_id');

        if( $prev == $new ){
            return Response::json(array('result'=>'OK:NOCHANGE','delta'=>0));
        }elseif($prev > $new){
            $delta = $prev - $new;
            $action = 'sub';

        }elseif($prev < $new){
            $delta = $new - $prev;
            $action = 'add';
        }

        if($action == 'sub'){
            $trx = Transaction::where('sessionId',$sessionId)->where('SKU',$sku)->take($delta)->get();
            $count = 0;
            foreach($trx as $tr){
                $su = Stockunit::find($tr->unitId);
                $su->status = 'available';
                $su->save();

                $tr->delete();
                $count++;
            }

            return Response::json(array('result'=>'OK:SUB','delta'=>$delta,'affected'=>$count));

        }else{
            $result = Commerce::addToCart($sku, $delta, $sessionId, $outlet_id);
            return Response::json(array('result'=>'OK:ADD','delta'=>$delta,'affected'=>$result));
        }

    }

    private function clearCart(){
        $user = Buyer::find(Auth::user()->_id);
        $user->activeCart = '';
        $user->save();
    }

}