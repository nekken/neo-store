<?php
namespace NeoStore\Entity;

class Transaction
{
    const TYPE_PAYMENT = 1;
    const TYPE_REFUND = 2;
    
    protected $amount;
    
    protected $referenceCode;
    
    protected $currency;
    
    protected $id;
    
    protected $status;
    
    protected $type;
}