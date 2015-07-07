<?php
namespace NeoStore\Entity;

use NeoStore\EntityHelper;
use Doctrine\Common\Collections\ArrayCollection;
class OrderReturn
{
    protected $id;
    
    protected $note;
    
    protected $order;
    
    protected $returnedItems;
    
    protected $status;
    
    public function __construct($data)
    {
        $this->returnedItems = new ArrayCollection();
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
     * @return the $note
     */
    public function getNote ()
    {
        return $this->note;
    }

	/**
     * @return the $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @return the $status
     */
    public function getStatus ()
    {
        return $this->status;
    }

	/**
     * @param field_type $note
     */
    public function setNote ($note)
    {
        $this->note = $note;
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
     * @param field_type $status
     */
    public function setStatus ($status)
    {
        $this->status = $status;
        return $this;
    }
	/**
     * @return ArrayCollection $returnedItems
     */
    public function getReturnedItems ()
    {
        return $this->returnedItems;
    }

	/**
     * @param ArrayCollection $returnedItems
     */
    public function setReturnedItems ($returnedItems)
    {
        $this->returnedItems = $returnedItems;
        return $this;
    }
}