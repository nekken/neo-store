<?php
use NeoStore\Cart;
class TaxTest extends PHPUnit_Framework_TestCase
{
    public function testTypeNormal()
    {
        $order = Cart::getOrder();
        
        $item = $order->addItem(array(
                "name" => "test product",
                "description" => "Product description...",
                "price" => 100,
                "quantity" => 1,
                "taxable" => true,
                "identifier" => 1
        ));
        
        $order->editShippingAddress(TestFactory::createQuebecAddress());
        
        $PST = $order->getTaxByTitle("PST");
        $GST = $order->getTaxByTitle("GST");
        
        $this->assertEquals(5, $GST->getAmount());
        $this->assertEquals(9.98, $PST->getAmount());
        $this->assertEquals(14.98, $order->getAmountTaxes());
        $this->assertEquals(14.98, $item->getAmountTaxes());
    }
    
    public function testTypeHarmonized()
    {
        $order = Cart::getOrder();
        
        $item = $order->addItem(array(
                "name" => "test product",
                "description" => "Product description...",
                "price" => 100,
                "quantity" => 1,
                "taxable" => true,
                "identifier" => 1
        ));
        
        $order->editShippingAddress(TestFactory::createOntarioAddress());
        
        $GST = $order->getTaxByTitle("GST");
        $HST = $order->getTaxByTitle("HST");
        
        $this->assertEquals(0, $GST->getAmount());
        $this->assertEquals(13, $HST->getAmount());
        $this->assertEquals(13, $order->getAmountTaxes());
        $this->assertEquals(13, $item->getAmountTaxes());
    }
    
    public function testTypeComposed()
    {
        /**
         * @todo calcul composé non-utilisé pour l'instant
         */
    }
    
    public function setUp()
    {
        Cart::getIdentifier()->regenerate();
    }
}