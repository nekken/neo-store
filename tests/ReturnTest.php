<?php
use NeoStore\Cart;
use NeoStore\Entity\Order;
class ReturnTest extends PHPUnit_Framework_TestCase
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
        
        $item2 = $order->addItem(array(
                "name" => "test product 2",
                "description" => "Product description...",
                "price" => 50,
                "quantity" => 2,
                "taxable" => true,
                "identifier" => 2
        ));
        
        $order->editShippingAddress(TestFactory::createQuebecAddress());
    
        $this->assertEquals(200, Cart::getOrder()->getAmountSubTotal());

        $order->createReturn(array(
            "items" => array(
                array(
                    "identifier" => 2,
                    "quantity" => 1,
                ),
            ),
            "note" => "Client not satisfied with quality"
        ));
        
        $this->assertEquals(150, Cart::getOrder()->getAmountSubTotal());
        
        $order->createReturn(array(
            "items" => array(
                array(
                    "identifier" => 1,
                    "quantity" => 1,
                ),
            ),
            "note" => "Client not satisfied with quality"
        ));
        
        $this->assertEquals(50, Cart::getOrder()->getAmountSubTotal());
        
        $this->setExpectedException('Exception', '', Order::EXCEPTION_RETURN_ITEM_QUANTITY);
        
        $order->createReturn(array(
                "items" => array(
                        array(
                                "identifier" => 2,
                                "quantity" => 2,
                        ),
                ),
                "note" => "Client not satisfied with quality"
        ));
    }
    
    public function setUp()
    {
        Cart::getIdentifier()->regenerate();
    }
}