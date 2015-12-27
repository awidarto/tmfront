<?php

class Emailer{
    public function __construct()
    {

    }

    public static function transfersuccess(){

    }

    public static function sendnotification($data,$template = null){

            //$sales = $sales->toArray();
            $content = '';

            $template = is_null($template)?'emails.confirmation':$template;

            try{

                Mail::send($template,array('body'=>$content, 'data'=>$data), function($message) use ($data){

                    $fullname = $data['name'];

                    $to = $data['email'];

                    $subject = $data['subject'];

                    $message->to($to, $fullname);

                    $message->subject($subject);


                    $message->to(Config::get('shop.admin_email') );

                    $message->to( Options::get( 'sales_notification_email' ,Config::get('shop.admin_email'))  );

                    $headers = $message->getHeaders();
                    $headers->addTextHeader('X-MC-PreserveRecipients', 'false');

                    //$message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
                });

                return true;

            }catch(Exception $e){

                return false;
            }


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

                    $message->cc( Options::get( 'sales_notification_email' ,Config::get('shop.admin_email'))  );

                    //$message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
                });

                return true;

            }catch(Exception $e){

                return false;
            }


    }
}