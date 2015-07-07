<?php
namespace NeoStore\Entity;

class TaxRate
{
    protected $percentage;

    protected $title;
    
    protected $type;
    
    public function __construct($data=array())
    {
        \NeoStore\EntityHelper::setObjectFromArray($this, $data);
    }
    
	/**
     * @return the $percentage
     */
    public function getPercentage ()
    {
        return $this->percentage;
    }

	/**
     * @return the $title
     */
    public function getTitle ()
    {
        return $this->title;
    }

	/**
     * @param field_type $percentage
     */
    public function setPercentage ($percentage)
    {
        $this->percentage = $percentage;
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
     * @return the $type
     */
    public function getType ()
    {
        return $this->type;
    }

	/**
     * @param field_type $type
     */
    public function setType ($type)
    {
        $this->type = $type;
        return $this;
    }

}