<?php
namespace Doku;

class Doku
{
	private $url = "http://103.10.129.17/Suite/Receive";
	private $DokuParams;
	public $postFields;
	
	public function __construct(DokuParams $params)
	{
		$this->DokuParams = $params;		
		$this->postFields = $this->generatePostFields();
	}
	
	private function generatePostFields()
	{
		$postfield = array(
			"MAILID" => $this->DokuParams->MAILID,
			"CHAINMERCHANT" => $this->DokuParams->CHAINMERCHANT,
			"AMOUNT" => $this->DokuParams->AMOUNT,
			"PURCHASEAMOUNT" => $this->DokuParams->PURCHASEAMOUNT,
			"TRANSIDMERCHANT" => $this->DokuParams->TRANSIDMERCHANT,
			"WORDS" => $this->DokuParams->WORDS,
			"REQUESTDATETIME" => $this->DokuParams->REQUESTDATETIME,
			"CURRENCY" => $this->DokuParams->CURRENCY,
			"PURCHASECURRENCY" => $this->DokuParams->PURCHASECURRENCY,
			"SESSIONID" => $this->DokuParams->SESSIONID,
			"NAME" => $this->DokuParams->NAME,
			"EMAIL" => $this->DokuParams->EMAIL,
			"ADDITIONALDATA" => $this->DokuParams->ADDITIONALDATA,
			"BASKET" => $this->DokuParams->BASKET,
			"SHIPPING_ADDRESS" => $this->DokuParams->SHIPPING_ADDRESS,
			"SHIPPING_CITY" => $this->DokuParams->SHIPPING_CITY,
			"SHIPPING_STATE" => $this->DokuParams->SHIPPING_STATE,
			"SHIPPING_COUNTRY" => $this->DokuParams->SHIPPING_COUNTRY,
			"SHIPPING_ZIPCODE" => $this->DokuParams->SHIPPING_ZIPCODE
		);
		return $postfield;	
	}
	
	public function requestDoku()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$retval = curl_exec($ch);
		curl_close($ch);
		return $retval;
	}
}