<?php
namespace NeoStore\Entity;

class Region
{
    protected $code;
    
    protected $country;
    
    protected $name;
    
    protected $taxRate;
    
    public function __construct($data=array())
    {
        \NeoStore\EntityHelper::setObjectFromArray($this, $data);
    }
    
	/**
     * @return the $code
     */
    public function getCode ()
    {
        return $this->code;
    }

	/**
     * @return the $country
     */
    public function getCountry ()
    {
        return $this->country;
    }

	/**
     * @return the $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return RegionTaxRate $taxes
     */
    public function getTaxRate ()
    {
        return $this->taxRate;
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
     * @param field_type $country
     */
    public function setCountry ($country)
    {
        $this->country = $country;
        return $this;
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
     * @param RegionTaxRate $taxRate
     */
    public function setTaxRate ($taxRate)
    {
        $taxRate->setRegion($this);
        $this->taxRate = $taxRate;
        return $this;
    }

}