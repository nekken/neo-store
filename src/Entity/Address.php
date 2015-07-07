<?php
namespace NeoStore\Entity;

class Address
{
    protected $address1;
    
    protected $address2;
    
    protected $city;
    
    protected $company;
    
    protected $firstName;
    
    protected $lastName;
    
    protected $phone;
    
    protected $region;
    
    protected $country;
    
    protected $zip;
    
    public function __construct($data=array())
    {
        \NeoStore\EntityHelper::setObjectFromArray($this, $data);
    }
    
	/**
     * @return the $address1
     */
    public function getAddress1 ()
    {
        return $this->address1;
    }

	/**
     * @return the $address2
     */
    public function getAddress2 ()
    {
        return $this->address2;
    }

	/**
     * @return the $city
     */
    public function getCity ()
    {
        return $this->city;
    }

	/**
     * @return the $company
     */
    public function getCompany ()
    {
        return $this->company;
    }

	/**
     * @return the $firstName
     */
    public function getFirstName ()
    {
        return $this->firstName;
    }

	/**
     * @return the $lastName
     */
    public function getLastName ()
    {
        return $this->lastName;
    }

	/**
     * @return the $phone
     */
    public function getPhone ()
    {
        return $this->phone;
    }

	/**
     * @return Region $region
     */
    public function getRegion ()
    {
        return $this->region;
    }

	/**
     * @return Country $country
     */
    public function getCountry ()
    {
        return $this->country;
    }

	/**
     * @return the $zip
     */
    public function getZip ()
    {
        return $this->zip;
    }

	/**
     * @param field_type $address1
     */
    public function setAddress1 ($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

	/**
     * @param field_type $address2
     */
    public function setAddress2 ($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

	/**
     * @param field_type $city
     */
    public function setCity ($city)
    {
        $this->city = $city;
        return $this;
    }

	/**
     * @param field_type $company
     */
    public function setCompany ($company)
    {
        $this->company = $company;
        return $this;
    }

	/**
     * @param field_type $firstName
     */
    public function setFirstName ($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

	/**
     * @param field_type $lastName
     */
    public function setLastName ($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

	/**
     * @param field_type $phone
     */
    public function setPhone ($phone)
    {
        $this->phone = $phone;
        return $this;
    }

	/**
     * @param Region $region
     */
    public function setRegion ($region)
    {
        $this->region = $region;
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
     * @param field_type $zip
     */
    public function setZip ($zip)
    {
        $this->zip = $zip;
        return $this;
    }

}