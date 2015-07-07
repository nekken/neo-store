<?php
namespace NeoStore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use NeoStore\EntityHelper;
class OrderItem
{
    protected $amountTaxes;

    protected $amountSubTotal;

    protected $amountReturned;
    
    protected $description;
    
    protected $id;

    protected $identifier;

    protected $name;

    protected $order;
    
    protected $price;
    
    protected $product;
    
    protected $quantity;
    
    protected $requiresShipping;
    
    protected $sku;
    
    protected $taxable;
    
    protected $taxes;
    
    protected $variant;
    
    protected $returns;
    
    public function __construct()
    {
        $this->taxes = new ArrayCollection();
        $this->returns = new ArrayCollection();
    }
    
    public function updateAmountSubTotal()
    {
        $this->updateAmountReturned();
        
        $amountSubTotal = 0;
        
        $amountSubTotal += $this->getPrice() * $this->getQuantity();
        $amountSubTotal -= $this->getAmountReturned();
        
        $this->setAmountSubTotal($amountSubTotal);
    }
   
    public function updateAmountReturned()
    {
        $amountReturned = 0;
        
        foreach($this->getReturns() as $return)
        {
            $amountReturned += $return->getQuantity() * $this->getPrice();
        }
        $this->setAmountReturned($amountReturned);
    }
    
    public function getAmountTaxable()
    {
        if($this->getTaxable() !== true)
        {
            return 0;
        }
        
        return $this->getSubTotal();
    }
    
    public function refreshTaxes()
    {
        $this->getTaxes()->clear();
        
        $composedTax = null;
        
        /* @var $tax OrderItemTax */
        foreach($this->getOrder()->getTaxes() as $tax)
        {
            $orderItemTax = new OrderItemTax();
            
            EntityHelper::setObjectFromArray($orderItemTax, array(
                "orderItem" => $this,
                "title" => $tax->getTitle(),
                "percentage" => $tax->getPercentage(),
                "type" => $tax->getType(),
                "composedTax" => $composedTax
            ));
            
            $this->getTaxes()->add($orderItemTax);
            
            $composedTax = $orderItemTax;
            
            $orderItemTax->refreshCalculator();
        }
    }
    
    public function updateTaxes()
    {
        $taxes = $this->getTaxes();
        
        $amountTaxes = 0;
        
        /* @var $tax OrderItemTax */
        foreach($taxes as $tax)
        {
            $tax->setAmountTaxable($this->getAmountTaxable());
            $tax->updateAmount();
            $amountTaxes += $tax->getAmount();
        }
        
//         /* @var $tax OrderItemTax */
//         foreach($this->getTaxes() as $tax)
//         {
//             $amountTaxes += $tax->getAmount();
//         }
        
        $this->setAmountTaxes($amountTaxes);
    }
    
    /**
     * @deprecated
     * @see $this->getAmountSubTotal()
     */
    public function getSubTotal()
    {
        return $this->getAmountSubTotal();
    }
    
	/**
     * @return the $description
     */
    public function getDescription ()
    {
        return $this->description;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return the $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return the $price
     */
    public function getPrice ()
    {
        return $this->price;
    }

	/**
     * @return the $product
     */
    public function getProduct ()
    {
        return $this->product;
    }

	/**
     * @return the $quantity
     */
    public function getQuantity ()
    {
        return $this->quantity;
    }
    
    public function getQuantityReturnable()
    {
        $quantityReturnable = $this->getQuantity();
        foreach($this->getReturns() as $return)
        {
            $quantityReturnable -= $return->getQuantity();
        }
        
        return $quantityReturnable;
    }

	/**
     * @return the $requiresShipping
     */
    public function getRequiresShipping ()
    {
        return $this->requiresShipping;
    }

	/**
     * @return the $sku
     */
    public function getSku ()
    {
        return $this->sku;
    }

	/**
     * @return the $taxable
     */
    public function getTaxable ()
    {
        return $this->taxable;
    }

	/**
     * @return ArrayCollection $taxes
     */
    public function getTaxes ()
    {
        return $this->taxes;
    }

	/**
     * @return the $variant
     */
    public function getVariant ()
    {
        return $this->variant;
    }

	/**
     * @param field_type $description
     */
    public function setDescription ($description)
    {
        $this->description = $description;
        return $this;
    }

	/**
     * @param field_type $name
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

	/**
     * @param field_type $price
     */
    public function setPrice ($price)
    {
        $this->price = $price;
        return $this;
    }

	/**
     * @param field_type $product
     */
    public function setProduct ($product)
    {
        $this->product = $product;
        return $this;
    }

	/**
     * @param field_type $quantity
     */
    public function setQuantity ($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

	/**
     * @param field_type $requiresShipping
     */
    public function setRequiresShipping ($requiresShipping)
    {
        $this->requiresShipping = $requiresShipping;
        return $this;
    }

	/**
     * @param field_type $sku
     */
    public function setSku ($sku)
    {
        $this->sku = $sku;
        return $this;
    }

	/**
     * @param field_type $taxable
     */
    public function setTaxable ($taxable)
    {
        $this->taxable = $taxable;
        return $this;
    }

	/**
     * @param field_type $taxes
     */
    public function setTaxes ($taxes)
    {
        $this->taxes = $taxes;
        return $this;
    }

	/**
     * @param field_type $variant
     */
    public function setVariant ($variant)
    {
        $this->variant = $variant;
        return $this;
    }
	/**
     * @return the $identifier
     */
    public function getIdentifier ()
    {
        return $this->identifier;
    }

	/**
     * @param field_type $identifier
     */
    public function setIdentifier ($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }
    
	/**
     * @return Order $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @param field_type $order
     */
    public function setOrder ($order)
    {
        $this->order = $order;
        return $this;
    }
	/**
     * @return the $amountTaxes
     */
    public function getAmountTaxes ()
    {
        return $this->amountTaxes;
    }

	/**
     * @param field_type $amountTaxes
     */
    public function setAmountTaxes ($amountTaxes)
    {
        $this->amountTaxes = $amountTaxes;
        return $this;
    }
	/**
     * @return ArrayCollection $returns
     */
    public function getReturns ()
    {
        return $this->returns;
    }
    
	/**
     * @param field_type $returns
     */
    public function setReturns ($returns)
    {
        $this->returns = $returns;
        return $this;
    }
	/**
     * @return the $amountReturned
     */
    public function getAmountReturned ()
    {
        return $this->amountReturned;
    }

	/**
     * @param field_type $amountReturned
     */
    public function setAmountReturned ($amountReturned)
    {
        $this->amountReturned = $amountReturned;
        return $this;
    }
	/**
     * @return the $amountSubTotal
     */
    public function getAmountSubTotal ()
    {
        return $this->amountSubTotal;
    }

	/**
     * @param field_type $amountSubTotal
     */
    public function setAmountSubTotal ($amountSubTotal)
    {
        $this->amountSubTotal = $amountSubTotal;
        return $this;
    }

    
}