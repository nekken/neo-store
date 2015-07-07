<?php
use NeoStore\Cart;
use NeoStore\Entity\OrderDiscountCode;
class DiscountTest extends PHPUnit_Framework_TestCase
{
    public function testFixed()
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
        
        $order->addDiscountCode(array(
                "amount" => 20,
                "type" => OrderDiscountCode::TYPE_FIXED
        ));
    
        $this->assertEquals(20, Cart::getOrder()->getAmountDiscounts());
        $this->assertEquals(80, Cart::getOrder()->getAmountSubTotal());
        
    }
    
    public function testPercent()
    {
        $order = Cart::getOrder();
        
        $item = $order->addItem(array(
                "name" => "test product",
                "description" => "Product description...",
                "price" => 100,
                "quantity" => 2,
                "taxable" => true,
                "identifier" => 1
        ));
        
        $order->editShippingAddress(TestFactory::createQuebecAddress());
        
        $order->addDiscountCode(array(
                "amount" => 20,
                "type" => OrderDiscountCode::TYPE_PERCENT
        ));
    
        $this->assertEquals(40, Cart::getOrder()->getAmountDiscounts());
        $this->assertEquals(160, Cart::getOrder()->getAmountSubTotal());
        
    }
    
    public function testBoth()
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
        
        $order->addDiscountCode(array(
                "amount" => 5,
                "title" => "Promo 5 dollars",
                "type" => OrderDiscountCode::TYPE_FIXED
        ));
    
        
        $order->addDiscountCode(array(
                "amount" => 10,
                "title" => "Promo 10%",
                "type" => OrderDiscountCode::TYPE_PERCENT
        ));
    
        $this->assertEquals(15, Cart::getOrder()->getAmountDiscounts());
        $this->assertEquals(85, Cart::getOrder()->getAmountSubTotal());
    }
    
    public function setUp()
    {
        Cart::getIdentifier()->regenerate();
    }
}