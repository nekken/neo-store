<?php
namespace NeoStore\Tax;

class Calculator implements CalculatorInterface
{
	/* (non-PHPdoc)
     * @see \NeoStore\Tax\AdaptorInterface::calculate()
     */
    public function calculate ($price, $percentage)
    {
        return $price * $percentage / 100;
    }
} 