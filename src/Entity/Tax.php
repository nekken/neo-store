<?php
namespace NeoStore\Entity;

use NeoStore\Tax\Calculator;
use NeoStore\Tax\CalculatorComposed;
class Tax
{
    const TYPE_NORMAL = 1;
    const TYPE_HARMONIZED = 2;
    const TYPE_COMPOSED = 3;
    
    protected $amount;

    protected $amountTaxable;
    
    protected $calculator;
    
    protected $composedTax;
    
    protected $percentage;
    
    protected $title;
    
    protected $type;
    
    public function updateAmount()
    {
        $amountTax = $this->getCalculator()->calculate($this->getAmountTaxable(), $this->getPercentage());
        $this->setAmount(round($amountTax,2));
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
    
    public function calculate($price)
    {
        return $this->getCalculator()->calculate($price, $this->getPercentage());
    }
    
    public function refreshCalculator()
    {
        switch($this->getType())
        {
            case self::TYPE_COMPOSED:
        
                $composedTax    = $this->getComposedTax();
                $composedRate   = ($composedTax) ? $composedTax->getPercentage() : 0 ;
                $calculator     = new CalculatorComposed($composedRate);
                break;
        
            case self::TYPE_HARMONIZED:
        
                $composedTax = $this->getComposedTax();
                $composedTax->setPercentage(0);
                $composedTax->updateAmount();
                $calculator  = new Calculator();
                break;
        
            case self::TYPE_NORMAL:
            default:
        
                $calculator = new Calculator();
                break;
        }
        
        $this->calculator = $calculator;
    }
    
	/**
     * @return Calculator $calculator
     */
    public function getCalculator ()
    {
        if(!$this->calculator)
        {
            $this->refreshCalculator();
        }
            
        return $this->calculator;
    }

	/**
     * @param field_type $calculator
     */
    public function setCalculator ($calculator)
    {
        $this->calculator = $calculator;
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
	/**
     * @return Tax
     */
    public function getComposedTax ()
    {
        return $this->composedTax;
    }

	/**
     * @param field_type $composedTax
     */
    public function setComposedTax ($composedTax)
    {
        $this->composedTax = $composedTax;
        return $this;
    }
	/**
     * @return the $amount
     */
    public function getAmount ()
    {
        return $this->amount;
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
     * @return the $amountTaxable
     */
    public function getAmountTaxable ()
    {
        return $this->amountTaxable;
    }

	/**
     * @param field_type $amountTaxable
     */
    public function setAmountTaxable ($amountTaxable)
    {
        $this->amountTaxable = $amountTaxable;
        return $this;
    }


}