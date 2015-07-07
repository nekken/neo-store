<?php
namespace NeoStore\Entity;

class Country
{
    protected $name;
    
    protected $code;
    
    protected $regions;
    
    protected $taxRate;
    
    public function __construct($data=array())
    {
        \NeoStore\EntityHelper::setObjectFromArray($this, $data);
    }
    
	/**
     * @return the $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return the $code
     */
    public function getCode ()
    {
        return $this->code;
    }

	/**
     * @return the $regions
     */
    public function getRegions ()
    {
        return $this->regions;
    }

	/**
     * @param field_type $name
     */
    public function setName ($name)
    {
        $this->name = $name;
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
     * @param field_type $regions
     */
    public function setRegions ($regions)
    {
        $this->regions = $regions;
        return $this;
    }
	/**
     * @return the $taxRate
     */
    public function getTaxRate ()
    {
        return $this->taxRate;
    }

	/**
     * @param CountryTaxRate $taxRate
     */
    public function setTaxRate ($taxRate)
    {
        $taxRate->setCountry($this);
        $this->taxRate = $taxRate;
        return $this;
    }


}