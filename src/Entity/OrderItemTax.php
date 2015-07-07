<?php
namespace NeoStore\Entity;

class OrderItemTax extends Tax
{
    protected $orderItem;
    
	/**
     * @return the $orderItem
     */
    public function getOrderItem ()
    {
        return $this->orderItem;
    }

	/**
     * @param field_type $orderItem
     */
    public function setOrderItem ($orderItem)
    {
        $this->orderItem = $orderItem;
        return $this;
    }
}