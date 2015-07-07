<?php
namespace NeoStore\Entity;

class OrderPayment extends Payment
{
    protected $type = self::TYPE_PAYMENT;
    protected $order;
}