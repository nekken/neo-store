<?php
namespace NeoStore\Tax;

interface CalculatorInterface
{
    public function calculate($price, $percentage);
}