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

    public function postRedirect()
    {
        $in = Input::get();
        return View::make('doku.redirect')->with('in',$in);
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

    // Basic SQL
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

    }

    public function missingMethod($parameter = array()){

    }

}