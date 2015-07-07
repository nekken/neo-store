<?php
namespace NeoStore\Entity;

class Payment extends Transaction
{
    protected $type = self::TYPE_PAYMENT;
}