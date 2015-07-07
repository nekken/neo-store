<?php
namespace NeoStore\Entity;

class OrderTax extends Tax
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