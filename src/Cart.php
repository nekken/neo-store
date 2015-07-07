<?php
namespace NeoStore;

use NeoStore\Identifier\Runtime;
use NeoStore\Entity\Order;
class Cart
{
    protected static $identifier;
    
    protected static $order;
    
    /**
     * 
     * @return \NeoStore\Entity\Order
     */
    public static function getOrder()
    {
        $identifier = self::getIdentifier();
        
        if(!isset(self::$order[$identifier->get()]))
        {
            $order = new Order();
            self::$order[$identifier->get()] = $order;
        }
        
        return self::$order[$identifier->get()];
    }
    
	/**
     * @return \NeoStore\IdentifierInterface
     */
    public static function getIdentifier ()
    {
        if(!Cart::$identifier)
        {
            Cart::$identifier = new Runtime();
        }
        return Cart::$identifier;
    }

	/**
     * @param field_type $identifier
     */
    public static function setIdentifier ($identifier)
    {
        Cart::$identifier = $identifier;
        return $this;
    }

}