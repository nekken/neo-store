<?php
namespace NeoStore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use NeoStore\EntityHelper;
class OrderShipping
{
    protected $amount;
    
    protected $id;
    
    protected $order;
    
    protected $taxes;
    
    protected $title;
    
    public function __construct($data=array())
    {
        $this->taxes = new ArrayCollection();
        
        EntityHelper::setObjectFromArray($this, $data);
    }
    
    public function getSubTotal()
    {
        return $this->amount;
    }
    
	/**
     * @return the $amount
     */
    public function getAmount ()
    {
        return $this->amount;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return the $taxes
     */
    public function getTaxes ()
    {
        return $this->taxes;
    }

	/**
     * @return the $title
     */
    public function getTitle ()
    {
        return $this->title;
    }

	/**
     * @param field_type $amount
     */
    public function setAmount ($amount)
    {
        $this->amount = $amount;
        return $this;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $taxes
     */
    public function setTaxes ($taxes)
    {
        $this->taxes = $taxes;
        return $this;
    }

	/**
     * @param field_type $title
     */
    public function setTitle ($title)
    {
        $this->title = $title;
        return $this;
    }
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