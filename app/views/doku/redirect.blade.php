@extends('layout.front')

@section('content')
        <?php
                $order_number = $in['TRANSIDMERCHANT'];
                $purchase_amt = $in['AMOUNT'];
                $status_code = $in['STATUSCODE'];
                $words = $in['WORDS'];
                $paymentchannel = $in['PAYMENTCHANNEL'];
                $session_id = $in['SESSIONID'];
                $paymentcode = $in['PAYMENTCODE'];

                $dokuform="<form name=\"param_pass\" id=\"param_pass\" method=\"post\" action=\"".URL::to('/')."/".Config::get('doku.result_url')."\">\n"; //example redirect link

                $dokuform.="<input name=\"order_number\" type=\"hidden\" id=\"order_number\" value=\"$order_number\">\n";
                $dokuform.="<input name=\"purchase_amt\" type=\"hidden\" id=\"purchase_amt\" value=\"$purchase_amt\">\n";
                $dokuform.="<input name=\"status_code\"  type=\"hidden\" id=\"status_code\"  value=\"$status_code\">\n";
                $dokuform.="<input name=\"words\"        type=\"hidden\" id=\"words\"        value=\"$words\">\n";
                $dokuform.="<input name=\"paymentchannel\" type=\"hidden\" id=\"paymentchannel\" value=\"$paymentchannel\">\n";
                $dokuform.="<input name=\"session_id\" type=\"hidden\" id=\"session_id\" value=\"$session_id\">\n";
                $dokuform.="<input name=\"paymentcode\" type=\"hidden\" id=\"paymentcode\" value=\"$paymentcode\">\n";

                $dokuform.="</form>\n";
                $dokuform.="<script language=\"JavaScript\" type=\"text/javascript\">";
                $dokuform.="document.getElementById('param_pass').submit();";
                $dokuform.="</script>";

                //FIREFOX FIX
                $redirect_url = str_replace('&amp;', '&', $redirect_url);

        ?>
        {{ $dokuform }}
        <noscript>
        If you are not redirected please <a href="<?php echo $redirect_url; ?>">click here</a> to confirm your order.
        </noscript>
@stop
