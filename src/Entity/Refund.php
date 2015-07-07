<?php
namespace NeoStore\Entity;

class Refund extends Transaction
{
    protected $type = self::TYPE_REFUND;
    
    protected $parentPayment;
}