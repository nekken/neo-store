<?php
namespace NeoStore\Entity;

class OrderShippingAddress extends Address
{
    protected $order;
    
	/**
     * @return the $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @param field_type $order
     */
    public function setOrder ($order)
    {
        $this->order = $order;
        return $this;
    }

}