<?php

class DokuController extends BaseController {

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

    }

    public function getPayment()
    {
        return View::make('doku.request');
    }

    public function postRedirect()
    {
        $in = Input::get();

        $transidmerchant = $in['TRANSIDMERCHANT'];
        $purchase_amt = $in['AMOUNT'];
        $status_code = $in['STATUSCODE'];
        $words = $in['WORDS'];
        $paymentchannel = $in['PAYMENTCHANNEL'];
        $session_id = $in['SESSIONID'];
        $paymentcode = $in['PAYMENTCODE'];

        /*
        "_id": ObjectId("54b793e9fa0c6de66b1aa014"),
   "approvalcode": "118274",
   "bank_issuer": "CIMB NIAGA",
   "cardnumber": "5***********3593",
   "cartId": "EIoHmzkrErXo",
   "created_at": ISODate("2015-01-15T10:18:17.460Z"),
   "payment_date_time": "20150115172002",
   "paymentchannel": "01",
   "paymentcode": "",
   "response_code": "0000",
   "session_id": "K2pt9Rypd0N03VUdqyen",
   "status": "SUCCESS",
   "statustype": "P",
   "totalamount": "27000.00",
   "transidmerchant": "QFiM7a2HU2DvN2pYslnG",
   "trxstatus": "Requested",
   "updated_at": ISODate("2015-01-15T10:20:03.191Z"),
   "verifyid": "",
   "verifyscore": "50",
   "verifystatus": "REVIEW",
   "words": "67a7b363c22b513883b78c622691939be8fd6bd1"
        */

        $trx = Doku::where('transidmerchant',$transidmerchant)->first();

        if($trx){
            $trx->statuscode = $status_code;
            $trx->paymentchannel = $paymentchannel;
            $trx->paymentcode = $paymentcode;
            $trx->save();
        }

        /*
        $ed['toimoicode'] = $transidmerchant;
        //$ed['transaction_code'] = $in['order_number'];
        //$ed['transferamount'] = $in['purchase_amt'];
        $ed['transaction_code'] = '';
        $ed['transferamount'] = '';

        $ed['createdDate'] = date('d-m-Y H:i:s', time() );
        //$ed['status'] = ($status_code == '0000')?'success':'failed';
        $ed['status'] = 'success';
        $ed['paymethod'] = 'Doku';

        Emailer::sendnotification($ed, 'emails.paymentconfirmation');
        */

        return View::make('doku.redirect')
            ->with('redirect_url',URL::to('doku/result'))
            ->with('in',$in);
    }

    public function postNotify()
    {
        $in = Input::get();

        if($in['TRANSIDMERCHANT']) {
            $order_number = $in['TRANSIDMERCHANT'];
        }else {
            $order_number = 0;
        }
        $totalamount = $in['AMOUNT'];
        $words    = $in['WORDS'];
        $statustype = $in['STATUSTYPE'];
        $response_code = $in['RESPONSECODE'];
        $approvalcode   = $in['APPROVALCODE'];
        $status         = $in['RESULTMSG'];
        $paymentchannel = $in['PAYMENTCHANNEL'];
        $paymentcode = $in['PAYMENTCODE'];
        $session_id = $in['SESSIONID'];
        $bank_issuer = $in['BANK'];
        $cardnumber = $in['MCN'];
        $payment_date_time = $in['PAYMENTDATETIME'];
        $verifyid = $in['VERIFYID'];
        $verifyscore = $in['VERIFYSCORE'];
        $verifystatus = $in['VERIFYSTATUS'];

        $doku = Doku::where('transidmerchant',$order_number)
                    ->where('trxstatus','Requested')
                    ->first();

        if($doku){
            $hasil=$doku->transidmerchant;
            $amount=$doku->totalamount;
        }else{
            $hasil = false;
            $amount = 0;
        }

        if (!$hasil) {

            echo 'Stop1';

        } else {

            if ($status=='SUCCESS') {
                $doku = Doku::where('transidmerchant',$order_number)->first();
                if($doku){
                    $doku->totalamount = $in['AMOUNT'];
                    $doku->words    = $in['WORDS'];
                    $doku->statustype = $in['STATUSTYPE'];
                    $doku->response_code = $in['RESPONSECODE'];
                    $doku->approvalcode   = $in['APPROVALCODE'];
                    $doku->status         = $in['RESULTMSG'];
                    $doku->paymentchannel = $in['PAYMENTCHANNEL'];
                    $doku->paymentcode = $in['PAYMENTCODE'];
                    $doku->session_id = $in['SESSIONID'];
                    $doku->bank_issuer = $in['BANK'];
                    $doku->cardnumber = $in['MCN'];
                    $doku->payment_date_time = $in['PAYMENTDATETIME'];
                    $doku->verifyid = $in['VERIFYID'];
                    $doku->verifyscore = $in['VERIFYSCORE'];
                    $doku->verifystatus = $in['VERIFYSTATUS'];

                    $doku->save();
                }else{
                    print 'Stop2';
                }

            } else {
                $doku = Doku::where('transidmerchant',$order_number)->first();
                if($doku){
                    $doku->trxstatus = 'Failed';
                    $doku->save();
                }else{
                    print 'Stop3';
                }
            }

            print 'Continue';

        }

    }

    public function postResult()
    {

        $in = Input::get();

        /*
        [order_number] => ZgcptW3fWNrw3nPvUF37
        [purchase_amt] => 316000.00
        [status_code] => 5510
        [words] => c71adbb806150a7edfa5987ced0d41830f73fe61
        [paymentchannel] => 01
        [session_id] => tnHU8PMiVRPGw2JifzJX
        [paymentcode] =>
        */

        $doku = Doku::where('transidmerchant',$in['order_number'])->first();

        $ed['toimoicode'] = $doku->cartId;
        //$ed['transaction_code'] = $in['order_number'];
        //$ed['transferamount'] = $in['purchase_amt'];
        $ed['transaction_code'] = '';
        $ed['transferamount'] = '';

        $status = 'failed';

        if($in['status_code'] == '0000'){
            $status = 'success';
        }

        $ed['createdDate'] = date('d-m-Y H:i:s', time() );
        $ed['status'] = $status;
        $ed['paymethod'] = 'Doku';

        Emailer::sendnotification($ed, 'emails.paymentconfirmation');

        //print_r(Input::get());
        return View::make('doku.result')
            ->with('doku',$doku)
            ->with('in',$in);
    }

    public function getResult()
    {
        //print_r(Input::get());
        $in = Input::get();

        $doku = Doku::where('transidmerchant',$in['order_number'])->first();

        $ed['toimoicode'] = $doku->cartId;
        //$ed['transaction_code'] = $in['order_number'];
        //$ed['transferamount'] = $in['purchase_amt'];
        $ed['transaction_code'] = '';
        $ed['transferamount'] = '';


        $status = 'failed';

        if($in['status_code'] == '0000'){
            $status = 'success';
        }

        $ed['createdDate'] = date('d-m-Y H:i:s', time() );
        $ed['status'] = $status;
        $ed['paymethod'] = 'Doku';

        Emailer::sendnotification($ed, 'emails.paymentconfirmation');

        return View::make('doku.result')
            ->with('in',Input::get());
    }

    public function missingMethod($parameter = array()){

    }

}