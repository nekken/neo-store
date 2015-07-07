<?php
namespace NeoStore\Tax;

class CalculatorComposed implements CalculatorInterface
{
    protected $composedRate;
    
    public function __construct($composedRate)
    {
        $this->setComposedRate($composedRate);
    }
    
	/* (non-PHPdoc)
     * @see \NeoStore\Tax\AdaptorInterface::calculate()
     */
    public function calculate ($price, $percentage)
    {
        $rate = ($this->getComposedRate() / 100 * $percentage) + $percentage;
        return $price * $rate / 100;
    }
    
	/**
     * @return the $composedRate
     */
    public function getComposedRate ()
    {
        return $this->composedRate;
    }

	/**
     * @param field_type $composedRate
     */
    public function setComposedRate ($composedRate)
    {
        $this->composedRate = $composedRate;
        return $this;
    }

} 