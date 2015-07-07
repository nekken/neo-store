<?php
use NeoStore\Cart;
use NeoStore\Entity\Customer;
use NeoStore\EntityFactory;
use NeoStore\Entity\OrderDiscountCode;
class OrderTest extends PHPUnit_Framework_TestCase
{
    public function testAddToCart()
    {
        $order = Cart::getOrder();
        
        $item1 = $order->addItem(array(
                "name" => "Apple Watch Sport",
                "description" => "Fits 130–200mm wrists.",
                "price" => 349,
                "quantity" => 1,
                "taxable" => true,
                "identifier" => 1
        ));
        
        $item1Copy = $order->getItemByIdentifier(1);
        $this->assertEquals($item1, $item1Copy);
        $this->assertEquals("Apple Watch Sport", $item1->getName());
        $this->assertEquals(349, Cart::getOrder()->getAmountSubTotal());
        $this->assertEquals(1, Cart::getOrder()->getItems()->count());
    }
    
    public function testAddToCart2()
    {
        $order = Cart::getOrder();
        
        $item2 = $order->addItem(array(
                "name" => "iPhone 6 Silicone Case - Green",
                "price" => 35,
                "quantity" => 2,
                "taxable" => true,
                "identifier" => 2
        ));

        
        $item2Copy = $order->getItemByIdentifier(2);
    
        $this->assertEquals($item2, $item2Copy);
        $this->assertEquals("iPhone 6 Silicone Case - Green", $item2->getName());
        $this->assertEquals(419, Cart::getOrder()->getAmountSubTotal());
        $this->assertEquals(2, Cart::getOrder()->getItems()->count());
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionCode 1
     */
    public function testAddToCartException()
    {
        $order = Cart::getOrder();
        
        $item2 = $order->addItem(array(
                "name" => "iPhone 6 Silicone Case - Blue",
                "price" => 35,
                "quantity" => 1,
                "taxable" => true,
                "identifier" => 2
        ));
    }
    
    public function testRemoveFromCart()
    {
        $order = Cart::getOrder();
        $order->removeItemByIdentifier(1);
        
        $this->assertFalse($order->getItemByIdentifier(1));
        $this->assertEquals(70, Cart::getOrder()->getAmountSubTotal());
    }
    
    public function testUpdateItem()
    {
        $order = Cart::getOrder();
        
        $order->updateItem(2, array(
            "quantity" => 3
        ));
        
        $this->assertEquals(105, Cart::getOrder()->getAmountSubTotal());
    }
    
    public function testShipping()
    {
        $order = Cart::getOrder();
        
        $order->addShipping(array(
            "amount" => 10,
            "title" => "Livraison"
        ));
        
        $this->assertEquals(105, Cart::getOrder()->getAmountSubTotal());
        $this->assertEquals(115, Cart::getOrder()->getAmountTotal());
    }
    
    public function testDiscount()
    {
        $order = Cart::getOrder();
    
        $order->addDiscountCode(array(
                "amount" => 20,
                "type" => OrderDiscountCode::TYPE_FIXED
        ));
    
        $this->assertEquals(20, Cart::getOrder()->getAmountDiscounts());
    
        $order->addDiscountCode(array(
                "amount" => 10,
                "type" => OrderDiscountCode::TYPE_PERCENT
        ));
    
        $this->assertEquals(30.5, Cart::getOrder()->getAmountDiscounts());
        $this->assertEquals(74.5, Cart::getOrder()->getAmountSubTotal());
        $this->assertEquals(84.5, Cart::getOrder()->getAmountTotal());
    }
    
    public function testReturn()
    {
        $order = Cart::getOrder();
        $order->createReturn(array(
                "items" => array(
                    array(
                        "identifier" => 2,
                        "quantity" => 1,
                    ),
                ),
                "note" => "Client not satisfied with quality"
        ));
        $this->assertEquals(70, $order->getAmountItemsSubTotal());
        $this->assertEquals(27, $order->getAmountDiscounts());
        $this->assertEquals(43, $order->getAmountSubTotal());
    }
    
    public static function setUpBeforeClass()
    {
        Cart::getIdentifier()->regenerate();
    }
}