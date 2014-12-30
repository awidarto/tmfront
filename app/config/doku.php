<?php

return array(
        'redirect_url' => 'doku/redirect',
        'result_url' => 'doku/result',
        'notify_url' => 'doku/notify',

        'shared_key' => '5P6bc6P4nxAA',
        'dev_submit' => 'http://103.10.129.17/Suite/Receive',
        'dev_mallid' => '1091',

        'prod_submit' => 'https://pay.doku.com/Suite/Receive',
        'prod_mallid' => '882',
        'channel'=>array(
            //''=>'NONE',
            '01'=>'Visa/Mastercard',
            '04'=>'DokuWallet',
            '05'=>'Virtual Account'
        ),

    );