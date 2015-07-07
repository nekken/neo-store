<?php
namespace NeoStore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
class Customer
{
    protected $acceptsMarketing;
    
    protected $addresses;
    
    protected $createdAt;
    
    protected $email;
    
    protected $firstName;
    
    protected $id;
    
    protected $lastName;
    
    protected $note;
    
    protected $password;
    
    protected $verified;
    
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }
    
	/**
     * @return the $acceptsMarketing
     */
    public function getAcceptsMarketing ()
    {
        return $this->acceptsMarketing;
    }

	/**
     * @return the $addresses
     */
    public function getAddresses ()
    {
        return $this->addresses;
    }

	/**
     * @return the $createdAt
     */
    public function getCreatedAt ()
    {
        return $this->createdAt;
    }

	/**
     * @return the $email
     */
    public function getEmail ()
    {
        return $this->email;
    }

	/**
     * @return the $firstName
     */
    public function getFirstName ()
    {
        return $this->firstName;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return the $lastName
     */
    public function getLastName ()
    {
        return $this->lastName;
    }

	/**
     * @return the $note
     */
    public function getNote ()
    {
        return $this->note;
    }

	/**
     * @return the $password
     */
    public function getPassword ()
    {
        return $this->password;
    }

	/**
     * @return the $verified
     */
    public function getVerified ()
    {
        return $this->verified;
    }

	/**
     * @param field_type $acceptsMarketing
     */
    public function setAcceptsMarketing ($acceptsMarketing)
    {
        $this->acceptsMarketing = $acceptsMarketing;
        return $this;
    }

	/**
     * @param field_type $addresses
     */
    public function setAddresses ($addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }

	/**
     * @param field_type $createdAt
     */
    public function setCreatedAt ($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

	/**
     * @param field_type $email
     */
    public function setEmail ($email)
    {
        $this->email = $email;
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
     * @param field_type $note
     */
    public function setNote ($note)
    {
        $this->note = $note;
        return $this;
    }

	/**
     * @param field_type $password
     */
    public function setPassword ($password)
    {
        $this->password = $password;
        return $this;
    }

	/**
     * @param field_type $verified
     */
    public function setVerified ($verified)
    {
        $this->verified = $verified;
        return $this;
    }

}