<?php
namespace Doku;
class DokuParams
{
	public $MAILID = "";
	public $CHAINMERCHANT = "";
	public $AMOUNT = "";
	public $PURCHASEAMOUNT = "";
	public $TRANSIDMERCHANT = "";
	public $WORDS = "";
	public $REQUESTDATETIME = "";
	public $CURRENCY = "360"; //default to IDR
	public $PURCHASECURRENCY = "";
	public $SESSIONID = "";
	public $NAME = "";
	public $EMAIL = "";
	public $ADDITIONALDATA = "";
	public $BASKET = "";
	public $SHIPPING_ADDRESS = "";
	public $SHIPPING_CITY = "";
	public $SHIPPING_STATE = "";
	public $SHIPPING_COUNTRY = "";
	public $SHIPPING_ZIPCODE = "";
	
	private $shared_key;
	
	public function  __construct($key)
	{
		$this->shared_key = $key;
	}
	
	public function prepareAll()
	{
		$this->WORDS = $this->generateWords();
		$this->REQUESTDATETIME = date("YmdHis");
	}
	
	private function generateWords()
	{
		/*
		 * Hashed key combination encryption (use SHA1
			method). The hashed key generated from
			combining these parameters value in this order :
			AMOUNT+MALLID+ <shared key> +
			TRANSIDMERCHANT.
			For transaction with currency other than 360 (IDR),
			use :
			AMOUNT+MALLID+ <shared key> +
			TRANSIDMERCHANT + CURRENCY
		 */
		$retval = null;
		if($this->CURRENCY == "360")
		{
			$retval = sha1(
							$this->AMOUNT 
						. 	$this->MAILID
						.	$this->shared_key
						.	$this->TRANSIDMERCHANT 
					);
		}
		else 
		{
			$retval = sha1(
					$this->AMOUNT
					. 	$this->MAILID
					.	$this->shared_key
					.	$this->TRANSIDMERCHANT
					.	$this->CURRENCY
			);
				
		}
		return $retval;
	}
}
/*
 * TRANSIDMERCHANT AN …30 Transaction ID from Merchant X
6 WORDS AN …200 Hashed key combination encryption (use SHA1
method). The hashed key generated from
combining these parameters value in this order :
AMOUNT+MALLID+ <shared key> +
TRANSIDMERCHANT.
For transaction with currency other than 360 (IDR),
use :
AMOUNT+MALLID+ <shared key> +
TRANSIDMERCHANT + CURRENCY
X
7 REQUESTDATETIME N x YYYYMMDDHHMMSS X
8 CURRENCY N 3 ISO3166 , numeric code X
9 PURCHASECURRENCY N 3 ISO3166 , numeric code X
10 SESSIONID AN ...48 X
11 NAME AN …50 Travel Arranger Name / Buyer name X
12 EMAIL ANS …100 Customer email X
13 ADDITIONALDATA ANS 1024 Custom additional data for specific
Merchant use O
14 BASKET ANS ...1024 Show transaction description. Use comma to
separate each field
and semicolon for each item.
Item1,1000.00,2,20000.00;item2,15000.00,2,3000
0.00
X
DOKU OneCheckout Integration Guide – Single API Payments
Confidential 2
15 SHIPPING_ADDRESS ANS ...100 Shipping address contains street and number O
16 SHIPPING_CITY ANS ...100 City name O
17 SHIPPING_STATE AN …100 State / province name O
18 SHIPPING_COUNTRY A 2 ISO3166 , alpha-2 O
19 SHIPPING_ZIPCODE
 */