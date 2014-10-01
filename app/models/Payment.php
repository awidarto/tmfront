<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Payment extends Eloquent {

    protected $collection = 'payments';
    protected $fillable = array('*');
}