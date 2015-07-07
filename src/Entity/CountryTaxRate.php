<?php
namespace NeoStore\Entity;

class CountryTaxRate extends TaxRate
{
    protected $country;
    
	/**
     * @return the $country
     */
    public function getCountry ()
    {
        return $this->country;
    }

	/**
     * @param field_type $country
     */
    public function setCountry ($country)
    {
        $this->country = $country;
        return $this;
    }
}