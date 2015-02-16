<?php

class Emailer{
    public function __construct()
    {

    }

    public static function transfersuccess(){

    }

    public static function confirmationsuccess($sales){

            //$sales = $sales->toArray();
            $content = '';

            try{

                Mail::send('emails.confirmation',array('body'=>$content), function($message) use ($sales){

                    $fullname = $sales['buyer_name'];

                    $to = $sales['buyer_email'];

                    $message->to($to, $fullname);

                    $message->subject('Payment Confirmation');

                    $message->cc('toimoiindonesia@gmail.com');

                    //$message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
                });

                return true;

            }catch(Exception $e){

                return false;
            }


    }
}