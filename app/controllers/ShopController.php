<?php
//use Doku\Doku;
//use Doku\DokuParams;

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

        Breadcrumbs::setDivider('');
        Breadcrumbs::setCssClasses('breadcrumb');
        Breadcrumbs::addCrumb('Home',URL::to('/'));
    }


    public function getIndex()
    {
        $headslider = Homeslider::where('publishing','published')
                        ->where('widgetLocation','Home Top')
                        ->orderBy('sequence','asc')
                        ->get()->toArray();

        return View::make('pages.home');
    }

    public function getConfirm()
    {
        return View::make('pages.confirm');
    }

    public function postConfirm()
    {
        $in = Input::get();
        $in['createdDate'] = new MongoDate();
        $in['lastUpdate'] = new MongoDate();

        $sid = $in['toimoicode'];

        $cid = Confirmation::insertGetId($in);

        if($cid){
            $result = true;
            $sales = Sales::where('sessionId',$sid)->first();
            $sales->transactionstatus = 'confirmed';
            $msales = $sales->toArray();
            $sales->save();
            $mailres = Emailer::confirmationsuccess($msales);
        }else{
            $result = false;
        }

        return View::make('pages.confirmresult')->with('result',$result)->with('email',$mailres);

    }

    public function getDetail($id = null)
    {

        $product = Product::find($id);

        Breadcrumbs::addCrumb($product->category,URL::to('shop/collection').'/'.$product->categoryLink);

        Breadcrumbs::addCrumb($product->itemDescription,'detail');

        $color_variant = array();

        if(isset($product->colorVariantArray)){
            if( count( $product->colorVariantArray ) ){
                $color_variant = Product::whereIn('SKU',$product->colorVariantArray)->get();
            }
        }

        return View::make('pages.detail')->with('product',$product)->with('colors',$color_variant);
    }

    public function getCancel()
    {
        if(isset(Auth::user()->activeCart) && Auth::user()->activeCart != ''){
            $units = Transaction::where('sessionId', Auth::user()->activeCart )->get();

            Payment::where('sessionId', Auth::user()->activeCart )->update(array('sessionStatus'=>'canceled'));
            Transaction::where('sessionId', Auth::user()->activeCart )->update(array('sessionStatus'=>'canceled'));
            Sales::where('sessionId', Auth::user()->activeCart )->update(array('payment.sessionStatus'=>'canceled','transactionstatus'=>'canceled'));
            //update stockunit status
            $sales = Sales::where('sessionId', Auth::user()->activeCart )->first();
            if($units){
                foreach($units as $u){
                    //print_r($u);
                    Stockunit::where('_id', $u->unitId )->update(array('status'=>'available'));
                }
            }

            Auth::user()->activeCart = '';
            User::where('_id',Auth::user()->_id)->update(array('activeCart'=>''));

            return Redirect::to('shop/cart');

        }
    }

    public function getCollection($category = 'art')
    {

        $page = Input::get('page');
        $page = (is_null($page))?0:$page;

        $perpage = Config::get('shop.pagination_itemperpage');

        $categories = Prefs::getProductCategory()->productCatToSelection('slug', 'title', false );

        $products = Product::where('categoryLink',$category)
                        ->where('status','active')
                        ->where('colorVariantParent','yes')
                        ->orderBy('itemDescription','asc')
                        ->orderBy('colour','asc')
                        ->skip($page * $perpage)
                        ->take($perpage)
                        ->get()->toArray();

        $currentcount = count($products);

        $total_found = Product::where('categoryLink',$category)
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

    public function postAddtocart()
    {
        if(isset(Auth::user()->activeCart) && Auth::user()->activeCart != '' ){
            $cart = Auth::user()->activeCart;
        }else{
            $cart = str_random(12);
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

        $nocart = ($session_id == '')?false:true;

        return View::make('pages.cart')->with('itemtable',$itemtable)->with('nocart',$nocart);
    }

    public function postCart()
    {

    }

    public function getMethods()
    {
        $itemtable = '';
        $session_id = Auth::user()->activeCart;
        $trx = Transaction::where('sessionId',$session_id)->get()->toArray();
        //$pay = Payment::where('sessionId',$session_id)->first()->toArray();

        $tab = array();
        foreach($trx as $t){

            $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
            $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
            $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['weight'] = ( isset($tab[ $t['SKU'] ]['weight']) )? $tab[ $t['SKU'] ]['weight']:1;

        }

        $tab_data = array();
        $gt = 0;
        $weight = 0;
        foreach($tab as $k=>$v){
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>$v['qty'], 'attr'=>'class="center"'),
                    array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                    array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                );
            $gt += $v['total'];
            $weight += ($v['qty'] * $v['weight']);
        }

        //$dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
        //$tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

        $dc = '';
        $tc = '';

        $totalform = Former::hidden('totalprice',$gt);
        $totalcost = Former::hidden('totalcost','');
        $deliverycost = Former::hidden('deliverycost','');

        $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
        $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
        $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.method')->with('itemtable',$itemtable)->with('weight',$weight);

    }

    public function postMethods(){

        $datain = Input::get();

        $validator = array(
            'total_charge'=>'required',
            'delivery_charge'=>'required'
        );


        $validation = Validator::make($input = $datain, $validator);

        if($validation->fails()){

            Session::flash('methodFail', 'Please select delivery method and tariff.');

            return Redirect::to('shop/methods')->withErrors($validation)->withInput(Input::all());

        }else{

            $session_id = Auth::user()->activeCart;
            $trx = Transaction::where('sessionId',$session_id)->first();
            $pay = Payment::where('sessionId',$session_id)->first();

            //print_r($datain);

            //print_r($trx->toArray());
            /*
            [totalprice] => 4650000
            [deliverycost] =>
            [delivery_charge] => 385000
            [totalcost] =>
            [total_charge] => 5035000
            [status] => review
            [jne_origin] => CGK10000
            [jne_dest] => BDO10000
            [jne_weight] => 2
            [jne_tariff] => 385000
            */
            if($pay){

            }else{
                $pay = new Payment();
                $pay->sessionId = $session_id;
                $pay->createdDate = new MongoDate();
                $pay->sessionStatus = 'review';
                $pay->cc_amount = '';
                $pay->cc_number = '';
                $pay->cc_expiry = '';
                $pay->dc_amount = '';
                $pay->dc_number = '';
                $pay->cash_amount = 0;
                $pay->cash_change = 0;
                $pay->lastUpdate = new MongoDate();
                $pay->invoice_number = '';
            }

            $pay->by_name = $datain['recipientname'];
            $pay->by_address = $datain['shipping_address'];
            $pay->payable_amount = $datain['totalprice'];
            $pay->delivery_charge = $datain['delivery_charge'];
            $pay->total_charge_after_delivery = $datain['total_charge'];
            $pay->jne_origin = $datain['jne_origin'];
            $pay->delivery_method = 'JNE';
            $pay->jne_dest = $datain['jne_dest'];
            $pay->jne_weight = $datain['jne_weight'];
            $pay->jne_tariff = $datain['jne_tariff'];
            $pay->status = 'setdelivery';

            $pay->save();

            return Redirect::to('shop/payment');

        }



    }

    public function getPayment()
    {
        $itemtable = '';
        $session_id = Auth::user()->activeCart;
        $trx = Transaction::where('sessionId',$session_id)->get()->toArray();
        $pay = Payment::where('sessionId',$session_id)->first()->toArray();

        $tab = array();
        foreach($trx as $t){

            $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
            $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
            $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['weight'] = ( isset($tab[ $t['SKU'] ]['weight']) )? $tab[ $t['SKU'] ]['weight']:1;

        }

        $tab_data = array();
        $gt = 0;
        $weight = 0;
        foreach($tab as $k=>$v){
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>$v['qty'], 'attr'=>'class="center"'),
                    array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                    array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                );
            $gt += $v['total'];
            $weight += ($v['qty'] * $v['weight']);
        }

        $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
        $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

        $totalform = Former::hidden('totalprice',$gt);
        $totalcost = Former::hidden('totalcost',$tc);
        $deliverycost = Former::hidden('deliverycost',$dc);

        $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
        $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
        $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.payment')
            ->with('itemtable',$itemtable)
            ->with('pay',$pay)
            ->with('weight',$weight);

    }

    public function postPaytransfer()
    {
        $session_id = Auth::user()->activeCart;
        $in = Input::get();
        //var_dump($in);

            $trx = Transaction::where('sessionId',$session_id)->get();

            $pay = Payment::where('sessionId',$session_id)->first();

            $apay = $pay->toArray();
            $pay->payment_method = 'transfer';

            $pay->status = 'final';
            $pay->save();

            $pay = $apay;

            $tab = array();

            $atrx = $trx->toArray();

            foreach($trx as $t){
                $t->sessionStatus = 'final';
                $t->save();

                $t = $t->toArray();

                $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
                $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
                $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
                $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];
                $tab[ $t['SKU'] ]['weight'] = ( isset($tab[ $t['SKU'] ]['weight']) )? $tab[ $t['SKU'] ]['weight']:1;

            }

            $tab_data = array();
            $gt = 0;
            $weight = 0;
            foreach($tab as $k=>$v){
                $tab_data[] = array(
                        array('value'=>$v['description'], 'attr'=>'class="left"'),
                        array('value'=>$v['qty'], 'attr'=>'class="center"'),
                        array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                        array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                    );
                $gt += $v['total'];
                $weight += ($v['qty'] * $v['weight']);
            }

            $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
            $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

            $totalform = Former::hidden('totalprice',$gt);
            $totalcost = Former::hidden('totalcost',$tc);
            $deliverycost = Former::hidden('deliverycost',$dc);

            $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
            $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
            $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

            $header = array(
                'things to buy',
                'unit',
                'tagprice',
                array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
                );

            $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
            $t = new HtmlTable($tab_data, $attr, $header);
            $itemtable = $t->build();
            /* make user no mre use of session id in auth session */

            $items = $trx;
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

            $outlet = Outlet::find(Config::get('site.outlet_id'));

            $sales->outletId = Config::get('site.outlet_id');
            $sales->outletName = $outlet->name;

            $sales->buyer_id = Auth::user()->_id;
            $sales->buyer_name = Auth::user()->fullname;
            $sales->buyer_address = Auth::user()->address;
            $sales->buyer_email = Auth::user()->email;
            $sales->buyer_city = Auth::user()->city;
            $sales->cc_amount = 0;
            $sales->cc_number = '';
            $sales->cc_expiry = '';
            $sales->dc_amount = 0;
            $sales->dc_number = '';
            $sales->payable_amount = $tc;
            $sales->cash_amount = 0;
            $sales->cash_change = 0;
            $sales->lastUpdate = new MongoDate();

            $sales->payment_method = 'transfer';
            $sales->transaction = $itarray;
            $sales->stockunit = $unitarr;
            $sales->payment = $apay;
            $sales->transactiontype = 'online';
            $sales->transactionstatus = 'checkout';
            $sales->save();

            //remove session data
            Auth::user()->activeCart = '';

            User::where('_id',Auth::user()->_id)->update(array('activeCart'=>''));

            return View::make('pages.transferlanding')
                ->with('itemtable',$itemtable)
                ->with('session_id',$session_id)
                ->with('pay',$pay)
                ->with('totalprice',$gt)
                ->with('totalcost',$tc)
                ->with('deliverycost',$dc)
                ->with('weight',$weight);

    }

    public function postPaydoku()
    {
        if(Config::get('doku.mode') == 'dev'){
            $doku_mall_id = Config::get('doku.dev_mallid');
            $doku_submit = Config::get('doku.dev_submit');
        }else{
            $doku_mall_id = Config::get('doku.prod_mallid');
            $doku_submit = Config::get('doku.prod_submit');
        }

        $session_id = Auth::user()->activeCart;
        $in = Input::get();
        //var_dump($in);

            $trx = Transaction::where('sessionId',$session_id)->get();

            $pay = Payment::where('sessionId',$session_id)->first();

            $apay = $pay->toArray();
            $pay->payment_method = 'transfer';

            $pay->status = 'final';
            $pay->save();

            $pay = $apay;

            $tab = array();

            $atrx = $trx->toArray();

            $basket = '';

            foreach($trx as $t){
                $t->sessionStatus = 'final';
                $t->save();

                $t = $t->toArray();

                $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
                $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
                $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
                $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];
                $tab[ $t['SKU'] ]['weight'] = ( isset($tab[ $t['SKU'] ]['weight']) )? $tab[ $t['SKU'] ]['weight']:1;

            }



            $tab_data = array();
            $gt = 0;
            $weight = 0;
            foreach($tab as $k=>$v){
                $tab_data[] = array(
                        array('value'=>$v['description'], 'attr'=>'class="left"'),
                        array('value'=>$v['qty'], 'attr'=>'class="center"'),
                        array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                        array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                    );

                $basket_data[] = $v['description'].','.number_format($v['tagprice'],2,'.','').','.$v['qty'].','.number_format($v['total'],2,'.','');

                $gt += $v['total'];
                $weight += ($v['qty'] * $v['weight']);
            }

            $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
            $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

            $totalform = Former::hidden('totalprice',$gt);
            $totalcost = Former::hidden('totalcost',$tc);
            $deliverycost = Former::hidden('deliverycost',$dc);

            $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
            $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
            $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

            $basket_data[] = 'JNE Shipping'.','.number_format($dc,2,'.','').','.'1'.','.number_format($dc,2,'.','');

            $header = array(
                'things to buy',
                'unit',
                'tagprice',
                array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
                );

            $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
            $t = new HtmlTable($tab_data, $attr, $header);
            $itemtable = $t->build();
            /* make user no mre use of session id in auth session */

            $items = $trx;
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

            $outlet = Outlet::find(Config::get('site.outlet_id'));

            $dokusession = Auth::user()->activeCart;

            //for test, randomize
            $dokusession = str_random(20);

            $payment_session = str_random(20);
            $request_time = date('YmdHis',time());

            $doku_shared_key = Config::get('doku.shared_key');

        //var msg = document.MerchatPaymentPage.AMOUNT.value + document.MerchatPaymentPage.MALLID.value + "5P6bc6P4nxAA" + document.MerchatPaymentPage.TRANSIDMERCHANT.value;

            $total_word = number_format($tc,2,'.','');

            $words_plain = $total_word.$doku_mall_id.$doku_shared_key.$dokusession;

            $trx_words = sha1( $words_plain );
            //$trx_words = $total_word.$doku_mall_id.$doku_shared_key.$dokusession;

            $sales->outletId = Config::get('site.outlet_id');
            $sales->outletName = $outlet->name;

            $sales->buyer_id = Auth::user()->_id;
            $sales->buyer_name = Auth::user()->fullname;
            $sales->buyer_address = Auth::user()->address;
            $sales->buyer_email = Auth::user()->email;
            $sales->buyer_city = Auth::user()->city;
            $sales->cc_amount = 0;
            $sales->cc_number = '';
            $sales->cc_expiry = '';
            $sales->dc_amount = 0;
            $sales->dc_number = '';
            $sales->payable_amount = $tc;
            $sales->cash_amount = 0;
            $sales->cash_change = 0;
            $sales->lastUpdate = new MongoDate();

            $sales->payment_method = 'doku';
            $sales->payment_id = $dokusession;
            $sales->payment_session = $payment_session;
            $sales->request_time = $dokusession;
            $sales->transaction = $itarray;
            $sales->stockunit = $unitarr;
            $sales->payment = $apay;
            $sales->transactiontype = 'online';
            $sales->transactionstatus = 'checkout';

            $sales->save();

                $doku = new Doku();

                $doku->cartId = Auth::user()->activeCart;
                $doku->transidmerchant = $dokusession ;
                $doku->totalamount = number_format($tc,2,'.','');
                $doku->words    = $trx_words;
                $doku->statustype = '';
                $doku->response_code = '';
                $doku->approvalcode   = '';
                $doku->status         = '';
                $doku->paymentchannel = '';
                $doku->paymentcode = '';
                $doku->session_id = $payment_session;
                $doku->bank_issuer = '';
                $doku->cardnumber = '';
                $doku->payment_date_time = '';
                $doku->verifyid = '';
                $doku->verifyscore = '';
                $doku->verifystatus = '';
                $doku->trxstatus = 'Requested';

                $doku->save();

            $basket = implode(',', $basket_data);

            //remove session data
            Auth::user()->activeCart = '';

            Former::framework('TwitterBootstrap3');

            return View::make('doku.checkout')
                ->with('doku_submit',$doku_submit)
                ->with('doku_mall_id',$doku_mall_id)
                ->with('itemtable',$itemtable)
                ->with('session_id',$session_id)
                ->with('basket',$basket)
                ->with('pay',$pay)
                ->with('payment_id',$dokusession)
                ->with('payment_session',$payment_session)
                ->with('request_time',$request_time)
                ->with('words',$trx_words)
                ->with('words_plain',$words_plain)
                ->with('totalprice',$gt)
                ->with('totalcost',$tc)
                ->with('deliverycost',$dc)
                ->with('weight',$weight);

    }

    public function postPaydokus()
    {
        $session_id = Auth::user()->activeCart;
        $in = Input::get();
        //var_dump($in);

        $trx = Transaction::where('sessionId',$session_id)->get();

        $pay = Payment::where('sessionId',$session_id)->first();

        $apay = $pay->toArray();
        $pay->payment_method = 'transfer';

        $pay->status = 'final';
        $pay->save();

        $pay = $apay;

        $tab = array();

        $atrx = $trx->toArray();

        foreach($trx as $t){
            $t->sessionStatus = 'final';
            $t->save();

            $t = $t->toArray();

            $tab[ $t['SKU'] ]['description'] = $t['productDetail']['itemDescription'];
            $tab[ $t['SKU'] ]['qty'] = ( isset($tab[ $t['SKU'] ]['qty']) )? $tab[ $t['SKU'] ]['qty'] + 1:1;
            $tab[ $t['SKU'] ]['tagprice'] = $t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['total'] = ( isset($tab[ $t['SKU'] ]['total']) )? $tab[ $t['SKU'] ]['total'] + $t['productDetail']['priceRegular']:$t['productDetail']['priceRegular'];
            $tab[ $t['SKU'] ]['weight'] = ( isset($tab[ $t['SKU'] ]['weight']) )? $tab[ $t['SKU'] ]['weight']:1;

        }

        $tab_data = array();
        $gt = 0;
        $weight = 0;
        foreach($tab as $k=>$v){
            $tab_data[] = array(
                    array('value'=>$v['description'], 'attr'=>'class="left"'),
                    array('value'=>$v['qty'], 'attr'=>'class="center"'),
                    array('value'=>Ks::idr($v['tagprice']), 'attr'=>'class="right"'),
                    array('value'=>Ks::idr($v['total']), 'attr'=>'class="right" id="total_'.$k.'"'),
                );
            $gt += $v['total'];
            $weight += ($v['qty'] * $v['weight']);
        }

        $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
        $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

        $totalform = Former::hidden('totalprice',$gt);
        $totalcost = Former::hidden('totalcost',$tc);
        $deliverycost = Former::hidden('deliverycost',$dc);

        $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
        $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
        $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();
        /* make user no mre use of session id in auth session */
        $items = $trx;
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

        $outlet = Outlet::find(Config::get('site.outlet_id'));

        $sales->outletId = Config::get('site.outlet_id');
        $sales->outletName = $outlet->name;

        $sales->buyer_id = Auth::user()->_id;
        $sales->buyer_name = Auth::user()->fullname;
        $sales->buyer_address = Auth::user()->address;
        $sales->buyer_city = Auth::user()->city;
        $sales->cc_amount = 0;
        $sales->cc_number = '';
        $sales->cc_expiry = '';
        $sales->dc_amount = 0;
        $sales->dc_number = '';
        $sales->payable_amount = $tc;
        $sales->cash_amount = 0;
        $sales->cash_change = 0;
        $sales->lastUpdate = new MongoDate();

        $sales->payment_method = 'transfer';
        $sales->transaction = $itarray;
        $sales->stockunit = $unitarr;
        $sales->payment = $apay;
        $sales->transactiontype = 'online';
        $sales->transactionstatus = 'checkout';
        $sales->save();

        //remove session data
        //Auth::user()->activeCart = '';

        $dokuParams = new DokuParams("5P6bc6P4nxAA");
        $dokuParams->MALLID = "882";
        $dokuParams->AMOUNT = $totalcost;
        $dokuParams->prepareAll();
        echo "\n\n<br/> =============================== \n\n<br/>";
        var_dump($dokuParams);
        $doku = new Doku($dokuParams);
        echo "\n\n<br/> =============================== \n\n<br/>";
        var_dump($doku);
        echo "\n\n<br/> =============================== \n\n<br/>";
        var_dump($doku->requestDoku());


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
        $pay = Payment::where('sessionId',$session_id)->first()->toArray();

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

        //print_r($pay);

        $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
        $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

        $totalform = Former::hidden('totalprice',$gt);
        $totalcost = Former::hidden('totalcost',$tc);
        $deliverycost = Former::hidden('deliverycost',$dc);

        $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
        $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
        $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

        //$tab_data[] = array('','',$totalform,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.receipt')
                ->with('itemtable',$itemtable)
                ->with('payment',$pay)
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
        $pay = Payment::where('sessionId',$session_id)->first()->toArray();

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

        $dc = (isset($pay['delivery_charge']))?$pay['delivery_charge']:'';
        $tc = (isset($pay['total_charge_after_delivery']))?$pay['total_charge_after_delivery']:'';

        $totalform = Former::hidden('totalprice',$gt);
        $totalcost = Former::hidden('totalcost',$tc);
        $deliverycost = Former::hidden('deliverycost',$dc);

        $tab_data[] = array('',$totalform,array('value'=>'Sub Total'.'<input type="hidden" value="'.$gt.'" id="sub-total" />', 'attr'=>'class="right" ' ) ,array('value'=>Ks::idr($gt), 'attr'=>'class="right"'));
        $tab_data[] = array('',$deliverycost,array('value'=>'Delivery Cost'.'<input type="hidden" name="delivery_charge" value="" id="delivery-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($dc), 'attr'=>'class="right" id="delivery-cost"'));
        $tab_data[] = array('',$totalcost,array('value'=>'Total'.'<input type="hidden" name="total_charge" value="" id="total-charge" />', 'attr'=>'class="right" ' ),array('value'=>Ks::idr($tc), 'attr'=>'class="right bold" id="total-cost"'));

        $header = array(
            'things to buy',
            'unit',
            'tagprice',
            array('value'=>'price to pay', 'attr'=>'style="text-align:right"')
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.printreceipt')->with('itemtable',$itemtable)->with('payment',$pay);

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

        $tab_data = array();

        foreach($purchases->toArray() as $p){
            $tab_data[] = array( date('d-m-Y H:i:s', $p['createdDate']->sec) ,
                '<a href="'.URL::to('shop/receipt/'.$p['sessionId'] ).'">'.$p['sessionId'].'</a>',
                array('value'=>'IDR '.Ks::idr($p['payable_amount']), 'attr'=>'style="text-align:right;font-weight:bold;"'),
                $p['transactionstatus'] );
        }

        $nopurchase = (count($tab_data) == 0)?false:true;

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        return View::make('pages.purchases')->with('itemtable',$itemtable)->with('nopurchase',$nopurchase);
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