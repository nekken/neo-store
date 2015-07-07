<?php
namespace NeoStore\Entity;

class RegionTaxRate extends TaxRate
{
    protected $countryTax;
    
    protected $region;
    
	/**
     * @return the $countryTax
     */
    public function getCountryTax ()
    {
        return $this->countryTax;
    }

	/**
     * @return the $region
     */
    public function getRegion ()
    {
        return $this->region;
    }

	/**
     * @param field_type $countryTax
     */
    public function setCountryTax ($countryTax)
    {
        $this->countryTax = $countryTax;
        return $this;
    }

	/**
     * @param field_type $region
     */
    public function setRegion ($region)
    {
        $this->region = $region;
        return $this;
    }

}