<?php
use NeoStore\Cart;
use NeoStore\EntityFactory;
use NeoStore\Entity\OrderDiscountCode;
use NeoStore\Entity\OrderShippingAddress;
use NeoStore\Entity\Country;
use NeoStore\Entity\CountryTaxRate;
use NeoStore\Entity\Region;
use NeoStore\Entity\RegionTaxRate;
class Order2Test extends PHPUnit_Framework_TestCase
{
    public function testAddToCart()
    {
        $order = Cart::getOrder();
        
        $item3 = $order->addItem(array(
            "name" => "Casque d'écoute avec microphone 185 - Modèle 185",
            "price" => 29.95,
            "quantity" => 1000,
            "taxable" => true,
            "identifier" => 3
        ));
        
        $this->assertEquals("Casque d'écoute avec microphone 185 - Modèle 185", $item3->getName());
        $this->assertEquals(29950, $order->getAmountSubTotal());
    }
    
    public function testShippingAddress()
    {
        Cart::getOrder()->editShippingAddress(TestFactory::createQuebecAddress());
    }
    
    public function testDiscount()
    {
        $order = Cart::getOrder();
    
        $order->addDiscountCode(array(
            "amount" => 10,
            "type" => OrderDiscountCode::TYPE_PERCENT
        ));
    
        $this->assertEquals(2995, $order->getAmountDiscounts());
    }
    
    public function testShipping()
    {
        $order = Cart::getOrder();
    
        $order->addShipping(array(
            "amount" => 10,
            "title" => "Livraison"
        ));
    
        $this->assertEquals(26955, $order->getAmountSubTotal());
    }
    
    public function testTax()
    {
        $order = Cart::getOrder();
        $this->assertEquals(4038.01, $order->getAmountTaxes());
    }
    
    public function testTotal()
    {
        $this->assertEquals(31003.01, Cart::getOrder()->getAmountTotal());
        
    }
    
    public static function setUpBeforeClass()
    {
        Cart::getIdentifier()->regenerate();
    }
}