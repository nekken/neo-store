<?php
namespace NeoStore\Entity;

use NeoStore\EntityHelper;
class OrderDiscountCode
{
    const TYPE_FIXED = 1;
    const TYPE_PERCENT = 2;
    
    protected $amount;
    
    protected $code;
    
    protected $id;
    
    protected $title;
    
    protected $order;
    
    protected $type;
    
    public function __construct($data=array())
    {
        EntityHelper::setObjectFromArray($this, $data);
    }
    
    public function getTotal()
    {
        switch($this->getType())
        {
            case self::TYPE_FIXED:
                return $this->getAmount();
                break;
            case self::TYPE_PERCENT:
                $rate = $this->getAmount() / 100;
                $subTotal = $this->getOrder()->getAmountItemsSubTotal();
                return $rate * $subTotal;
                break;
        }
        
        return 0;
    }
    
	/**
     * @return the $amount
     */
    public function getAmount ()
    {
        return $this->amount;
    }

	/**
     * @return the $code
     */
    public function getCode ()
    {
        return $this->code;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return the $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @return the $type
     */
    public function getType ()
    {
        return $this->type;
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
     * @param field_type $code
     */
    public function setCode ($code)
    {
        $this->code = $code;
        return $this;
    }

	/**
     * @param field_type $order
     */
    public function setOrder ($order)
    {
        $this->order = $order;
        return $this;
    }

	/**
     * @param field_type $type
     */
    public function setType ($type)
    {
        $this->type = $type;
        return $this;
    }
	/**
     * @return the $title
     */
    public function getTitle ()
    {
        return $this->title;
    }

	/**
     * @param field_type $title
     */
    public function setTitle ($title)
    {
        $this->title = $title;
        return $this;
    }


    
    
}