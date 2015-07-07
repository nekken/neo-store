<?php
namespace NeoStore\Entity;

use NeoStore\EntityHelper;
class OrderReturnItem
{
    protected $id;
    
    protected $orderItem;
    
    protected $return;
    
    protected $quantity;
    
    public function __construct($data=array())
    {
        EntityHelper::setObjectFromArray($this, $data);
    }
    
	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return the $orderItem
     */
    public function getOrderItem ()
    {
        return $this->orderItem;
    }

	/**
     * @return the $return
     */
    public function getReturn ()
    {
        return $this->return;
    }

	/**
     * @return the $quantity
     */
    public function getQuantity ()
    {
        return $this->quantity;
    }

	/**
     * @param OrderItem $orderItem
     */
    public function setOrderItem ($orderItem)
    {
        $orderItem->getReturns()->add($this);
        $this->orderItem = $orderItem;
        return $this;
    }

	/**
     * @param field_type $return
     */
    public function setReturn ($return)
    {
        $this->return = $return;
        return $this;
    }

	/**
     * @param field_type $quantity
     */
    public function setQuantity ($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

}