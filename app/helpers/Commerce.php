<?php

class Commerce{

    public static $available;
    public static $availableselarray;

    public static function getAvailableCount($sku,$outletid){
        $count = Stockunit::where('SKU',$sku)->where('outletId',$outletid)->where('status','available')->count();
        self::$available = $count;
        return new self;
    }

    public static function availableToSelection(){
        $selectarray = array();
        for($i = 1; $i <= self::$available;$i++ ){
            $selectarray[$i] = $i;
        }
        self::$availableselarray = $selectarray;
        return new self;
    }

    public static function availableToCount(){
        return self::$available;
    }

    public static function getCartItemCount($session,$outletid){
        if($session == ''){
            $count = 0;
        }else{
            $count = Transaction::where('sessionId',$session)->where('outletId',$outletid)->count();
        }
        return $count;
    }

    public static function availableSelectionToHtml($fieldname = 'quantity',$label = 'Qty'){
        return Former::select($fieldname,$label)->options(self::$availableselarray)->id($fieldname)->class('form-control input-sm')->style('width:75px;');
    }

    public static function updateStock($data, $positive = 'available', $negative = 'deleted'){

        //print_r($data);

        $outlets = $data['outlets'];
        $outletNames = $data['outletNames'];
        $addQty = $data['addQty'];
        $adjustQty = $data['adjustQty'];

        unset($data['outlets']);
        unset($data['outletNames']);
        unset($data['addQty']);
        unset($data['adjustQty']);

        $productDetail = Product::find($data['id'])->toArray();

        // year and month used fro batchnumber
        $year = date('Y', time());
        $month = date('m',time());


        for( $i = 0; $i < count($outlets); $i++)
        {

            $su = array(
                    'outletId'=>$outlets[$i],
                    'outletName'=>$outletNames[$i],
                    'productId'=>$data['id'],
                    'SKU'=>$data['SKU'],
                    'productDetail'=>$productDetail,
                    'status'=>$positive,
                    'createdDate'=>new MongoDate(),
                    'lastUpdate'=>new MongoDate()
                );

            if($addQty[$i] > 0){
                for($a = 0; $a < $addQty[$i]; $a++){
                    $su['_id'] = str_random(8);


                    $batchnumber = Prefs::GetBatchId($data['SKU'], $year, $month);

                    $su['_id'] = $data['SKU'].'|'.$batchnumber;

                    $history = array(
                        'datetime'=>new MongoDate(),
                        'action'=>'init',
                        'price'=>$productDetail['priceRegular'],
                        'status'=>$su['status'],
                        'outletName'=>$su['outletName']
                    );

                    $su['history'] = array($history);

                    Stockunit::insert($su);
                }
            }

            if($adjustQty[$i] > 0){
                $td = Stockunit::where('outletId',$outlets[$i])
                    ->where('productId',$data['id'])
                    ->where('SKU', $data['SKU'])
                    ->where('status','available')
                    ->orderBy('createdDate', 'asc')
                    ->take($adjustQty[$i])
                    ->get();

                foreach($td as $d){
                    $d->status = $negative;
                    $d->lastUpdate = new MongoDate();
                    $d->save();

                    $history = array(
                        'datetime'=>new MongoDate(),
                        'action'=>'delete',
                        'price'=>$d->priceRegular,
                        'status'=>$d->status,
                        'outletName'=>$d->outletName
                    );

                    $d->push('history', $history);
                }
            }

        }
    }


    public static function splitProductTag($tag, $as_array = true){
        $tag =explode(',', $tag);
        $products = Product::whereIn('SKU',$tag)->get();

        if($as_array){
            $products = $products->toArray();
        }

        return $products;
    }

    public static function addToCart($sku, $qty,$session, $outlet_id)
    {
        $entry = Stockunit::where('SKU','=', $sku)
            ->where('outletId',Config::get('site.outlet_id'))
            ->where('status','available')
            ->take($qty)->get();

            $cat_item = Product::where('SKU','=',$sku)->first();
            if($cat_item){
                $item_price = Commerce::getCatalogPrice( $cat_item->_id, Config::get('site.outlet_id'),0,false );
            }else{
                $item_price = 0;
            }



        $outlet = Outlet::find($outlet_id);

        if(count($entry) > 0){

            foreach($entry as $u){

                $ul = $u->toArray();

                $ul['scancheckDate'] = new MongoDate();
                $ul['createdDate'] = new MongoDate();
                $ul['lastUpdate'] = new MongoDate();
                $ul['action'] = 'addtocart';
                $ul['status'] = 'reserved';
                $ul['quantity'] = 1;

                if(Config::get('shop.cart_price') == 'catalog'){
                    if($item_price > 0){
                        $ul['unitPrice'] = $item_price;
                        $ul['unitTotal'] = $item_price;
                    }else{
                        $ul['unitPrice'] = $ul['productDetail']['priceRegular'];
                        $ul['unitTotal'] = $ul['productDetail']['priceRegular'];
                    }
                }else{
                    $ul['unitPrice'] = $ul['productDetail']['priceRegular'];
                    $ul['unitTotal'] = $ul['productDetail']['priceRegular'];
                }
                $ul['deliverTo'] = $outlet->name;
                $ul['deliverToId'] = $outlet_id;
                $ul['returnTo'] = $outlet->name;
                $ul['returnToId'] = $outlet_id;

                $ul['sessionId'] = $session;
                $ul['sessionStatus'] = 'open';

                $unit_id = $ul['_id'];

                unset($ul['_id']);

                $ul['unitId'] = $unit_id;

                Transaction::insert($ul);

                $history = array(
                    'datetime'=>new MongoDate(),
                    'action'=>'pos',
                    'price'=>$ul['productDetail']['priceRegular'],
                    'status'=>$ul['status'],
                    'outletName'=>$ul['outletName']
                );

                //change status to sold
                $u->status = 'reserved';
                $u->push('history', $history);

                $u->save();

            }

            $res = 'OK';

            $msg = 'item added';

            $rcount = count($entry);


        }else{
            $res = 'NOK';
            $msg = 'SKU: '.$sku.' <br />no longer available';
            $rcount = 0;
        }


        $total_price = Transaction::where('sessionId',$session)
                ->sum('unitPrice');

        $total_count = Transaction::where('sessionId',$session)
                ->count();

        $result = array(
            'total_price'=>$total_price,
            'total_count'=>$total_count,
            'item_count'=>$rcount,
            'session'=>$session,
            'result'=>$res,
            'msg'=>$msg
        );

        return $result;


    }

    public static function saveCart($in)
    {
            $trx = Payment::where('sessionId', $in['current_trx'])->first();

            if($trx){

            }else{
                $trx = new Payment();
                $trx->sessionId = $in['current_trx'];
                $trx->createdDate = new MongoDate();
                $trx->sessionStatus = 'open';
            }

            if($in['status'] == 'final'){
                $trx->sessionStatus = 'final';
            }

            $trx->by_name = $in['by_name'];
            $trx->by_address = $in['by_address'];
            $trx->cc_amount = $in['cc_amount'];
            $trx->cc_number = $in['cc_number'];
            $trx->cc_expiry = $in['cc_expiry'];
            $trx->dc_amount = $in['dc_amount'];
            $trx->dc_number = $in['dc_number'];
            $trx->payable_amount = $in['payable_amount'];
            $trx->cash_amount = $in['cash_amount'];
            $trx->cash_change = $in['cash_change'];
            $trx->lastUpdate = new MongoDate();

            $payment = $trx->toArray();

            $trx->save();


            if($in['status'] == 'final'){

                $itarray = array();
                $unitarr = array();

                $items = Transaction::where('sessionId',$in['current_trx'])->get();
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

                $sales = Sales::where('sessionId', $in['current_trx'])->first();

                if($sales){

                }else{
                    $sales = new Sales();
                    $sales->sessionId = $in['current_trx'];
                    $sales->createdDate = new MongoDate();
                }
                $sales->outletId = $outletId;
                $sales->outletName = $outletName;

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

                $sales->transaction = $itarray;
                $sales->stockunit = $unitarr;
                $sales->payment = $payment;
                $sales->transactiontype = 'pos';
                $sales->save();
            }

            return Response::json(array( 'result'=>'OK' ));

    }

    public static function getCatalogPrice($productId, $outletId, $defaultprice,$fordisplay = false)
    {
        $productId = new MongoId($productId);
        $l = Product::where('_id', $productId)->orderBy('createdDate','desc')->first();

        $has_discount = false;
        $regular = 0;
        if($l){
            if($l->priceRegular != 0 && $l->priceRegular != ''){
                $price = doubleval($l->priceRegular);
            }else{
                $price = $defaultprice;
            }

            if(strtotime($l->discFromDate) <= time() && strtotime($l->discToDate) >= time() ){
                if($l->priceRegular != 0 && $l->priceRegular != ''){
                    $price = $l->priceDiscount;
                    $has_discount = true;
                }
            }

        }else{
            $price = $defaultprice;
        }

        if(Config::get('shop.display_with_ppn')){
            $price = $price + ($price * Config::get('shop.ppn') );
            $priceBefore = $l->priceRegular + ($l->priceRegular * Config::get('shop.ppn') );
        }else{
            $priceBefore = $l->priceRegular;
        }

        if($fordisplay){
            if(Config::get('shop.display_with_ppn')){
                $price = $price + ($price * Config::get('shop.ppn') );
                $priceBefore = $l->priceRegular + ($l->priceRegular * Config::get('shop.ppn') );
            }
            return array('price'=>$price, 'before'=>$priceBefore, 'has_discount'=>$has_discount );
        }else{
            return $price;
        }
    }


    public static function getPrice($productId, $outletId, $defaultprice)
    {
        $productId = new MongoId($productId);
        $l = Discount::where('productId', $productId)
                ->where('outletId', $outletId )->orderBy('createdDate','desc')->first();

        if($l){
            if($l->regPrice != 0 && $l->regPrice != ''){
                return doubleval($l->regPrice);
            }else{
                return $defaultprice;
            }
        }else{
            return $defaultprice;
        }
    }

    public static function getDiscountPrice($productId, $outletId, $defaultprice)
    {
        $productId = new MongoId($productId);
        $l = Discount::where('productId', $productId)
                ->where('outletId', $outletId )->orderBy('createdDate','desc')->first();

        if($l){
            if($l->discount != '' && $l->discount != 0){
                if( doubleval($l->discount) <= 100){
                    return doubleval($l->regPrice) * ( doubleval($l->discount)/ 100);
                }else{
                    if($l->regPrice != 0 && $l->regPrice != ''){
                        return doubleval($l->regPrice);
                    }else{
                        return $defaultprice;
                    }
                }
            }else{
                if($l->regPrice != 0 && $l->regPrice != ''){
                    return doubleval($l->regPrice);
                }else{
                    return $defaultprice;
                }
            }
        }else{
            return $defaultprice;
        }
    }


}