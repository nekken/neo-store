<?php
namespace NeoStore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use NeoStore\EntityHelper;
use Doctrine\Common\Collections\Criteria;
use NeoStore\Entity\OrderDiscountCode;
use NeoStore\Entity\OrderTax;
use NeoStore\Tax\Calculator;
use NeoStore\Tax\CalculatorComposed;
class Order
{
    const EXCEPTION_ITEM_ALREADY_EXISTS = 1;
    const EXCEPTION_RETURN_ITEM_QUANTITY = 2;
    
    protected $amountDiscounts;
    
    protected $amountItemsSubTotal;
    
    protected $amountPaid;
    
    protected $amountRefunded;
    
    protected $amountShipping;

    protected $amountSubTotal;
    
    protected $amountTaxes;

    protected $amountTotal;
    
    protected $billingAddress;
    
    protected $cancelledAt;
    
    protected $closedAt;
    
    protected $createdAt;
    
    protected $currency;
    
    protected $customer;
    
    protected $discoundCodes;
    
    protected $email;
    
    protected $financialStatus;
    
    protected $fulfillments;
    
    protected $fulfillmentStatus;
    
    protected $id;
    
    protected $items;
    
    protected $returns;
    
    protected $shippingAddress;
    
    protected $shippings;
    
    protected $taxes;
    
    protected $taxesIncluded;
    
    protected $transactions;
    
    protected $updatedAt;
    
    public function __construct()
    {
        $this->fulfillments = new ArrayCollection();
        
        $this->items = new ArrayCollection();
        
        $this->returns = new ArrayCollection();
        
        $this->shippings = new ArrayCollection();
        
        $this->taxes = new ArrayCollection();
        
        $this->transactions = new ArrayCollection();
        
        $this->discoundCodes = new ArrayCollection();
    }
    
    public function createReturn($data=array())
    {
        if(!isset($data['items']))
        {
            throw new \Exception("Items are not set!");
        }
        
        $return = new OrderReturn($data);
        
        foreach($data['items'] as $item)
        {
            if(!isset($item['quantity']))
            {
                throw new \Exception("Quantity not set for returned item!");
            }
            
            if(!isset($item['identifier']))
            {
                throw new \Exception("Identifier not set for returned item!");
            }
            
            if($this->getItemByIdentifier($item['identifier']))
            {
                $orderItem = $this->getItemByIdentifier($item['identifier']);
                
                if($item['quantity'] > $orderItem->getQuantityReturnable())
                {
                    throw new \Exception("Quantity returned is higher than quantity returnable!", self::EXCEPTION_RETURN_ITEM_QUANTITY);
                }
                
                $returnedItem = new OrderReturnItem(array(
                    "orderItem" => $orderItem,
                    "return" => $return,
                    "quantity" => $item['quantity']
                ));
                
                $return->getReturnedItems()->add($returnedItem);
            }
        }
        
        $this->updateAmounts();
        
        return $return;
    }
    
    public function addShipping($data)
    {
        $data['order'] = $this;
        
        $shipping = new OrderShipping($data);
        
        $this->getShippings()->add($shipping);
        
        $this->updateAmounts();
    }
    
    public function addDiscountCode($data)
    {
        $data['order'] = $this;
        
        $discount = new OrderDiscountCode($data);
        
        $this->getDiscoundCodes()->add($discount);
        
        $this->updateAmounts();
    }
    
    public function updateAmounts()
    {
        $this->updateItemsSubTotal();
        $this->updateDiscounts();
        $this->updateSubTotal();
        $this->updateShipping();
        $this->updateTaxes();
        $this->updateTotal();
    }
    
    public function refreshTaxes()
    {
        $this->getTaxes()->clear();
        
        if(!$this->getShippingAddress())
        {
            return;
        }
        
        $shipping       = $this->getShippingAddress();
        
        $newCountryTax  = null;
        
        if($shipping->getCountry())
        {
            if($shipping->getCountry()->getTaxRate())
            {
                $cTaxRate = $shipping->getCountry()->getTaxRate();
                $newCountryTax = new OrderTax();
                
                EntityHelper::setObjectFromArray($newCountryTax, array(
                    "order" => $this,
                    "percentage" => $cTaxRate->getPercentage(),
                    "title" => $cTaxRate->getTitle(),
                    "type" => Tax::TYPE_NORMAL
                ));
                
                $this->getTaxes()->add($newCountryTax);
                
                $newCountryTax->refreshCalculator();
            }
        }
        
        if($shipping->getRegion())
        {
            if($shipping->getRegion()->getTaxRate())
            {
                $rTaxRate = $shipping->getRegion()->getTaxRate();
                $newRegionTax = new OrderTax();
                
                EntityHelper::setObjectFromArray($newRegionTax, array(
                    "order" => $this,
                    "composedTax" => $newCountryTax,
                    "title" => $rTaxRate->getTitle(),
                    "percentage" => $rTaxRate->getPercentage(),
                    "type" => $rTaxRate->getType()
                ));
                
                $this->getTaxes()->add($newRegionTax);
                
                $newRegionTax->refreshCalculator();
            }
        }
        
        /* @var $item OrderItem */
        foreach($this->getItems() as $item)
        {
            $item->refreshTaxes();
        }
        
        $this->updateAmounts();
    }
    
    public function updateShipping()
    {
        $amountShipping = 0;
        
        /* @var $shipping OrderShipping */
        foreach($this->getShippings() as $shipping)
        {
            $amountShipping += $shipping->getSubTotal();
        }
        
        $this->setAmountShipping($amountShipping);
    }
    
    public function updateTotal()
    {
        $amountTotal = 0;
        
        $amountTotal += $this->getAmountSubTotal();

        $amountTotal += $this->getAmountShipping();
        
        $amountTotal += $this->getAmountTaxes();
        
        $this->setAmountTotal($amountTotal);
    }
    
    public function updateDiscounts()
    {
        $amountDiscounts = 0;
       
        foreach($this->getDiscoundCodes() as $discount)
        {
            $amountDiscounts += $discount->getTotal();
        }
        
        $this->setAmountDiscounts($amountDiscounts);
    }
    
    public function updateTaxes()
    {
        $amountTaxes = 0;
        $amountTaxable = 0;
        
        /* @var $item OrderItem */
        foreach($this->getItems() as $item)
        {
            $item->updateTaxes();
            $amountTaxable = $item->getAmountTaxable();
        }
        
        $amountTaxable -= $this->getAmountDiscounts();
        $amountTaxable += $this->getAmountShipping();
        
        /* @var $tax OrderTax */
        foreach($this->getTaxes() as $tax)
        {
            $tax->setAmountTaxable($amountTaxable);
            $tax->updateAmount();
            $amountTaxes += $tax->getAmount();
        }
        
        $this->setAmountTaxes(round($amountTaxes,2));
    }
    
    public function updateItemsSubTotal()
    {
        $amountItemsSubTotal = 0;
        
        /* @var $item OrderItem */
        foreach($this->getItems() as $item)
        {
            $item->updateAmountSubTotal();
            $amountItemsSubTotal += $item->getSubTotal();
        }
        
        $this->setAmountItemsSubTotal($amountItemsSubTotal);
    }
    
    public function updateSubTotal()
    {
        $amountSubTotal = $this->getAmountItemsSubTotal() - $this->getAmountDiscounts();
        $this->setAmountSubTotal($amountSubTotal);
    }
    
    public function addItem($data)
    {
        if(isset($data['identifier']) && $this->getItemByIdentifier($data['identifier']) !== false)
        {
            throw new \Exception("Identifier for this item already exists, use updateItem() instead", self::EXCEPTION_ITEM_ALREADY_EXISTS);
        }
        
        $item = new OrderItem();
        
        EntityHelper::setObjectFromArray($item, $data);
        
        $item->setOrder($this);
        
        $this->getItems()->add($item);
        
        $this->updateAmounts();
        
        return $item;
    }
    
    public function removeItemByIdentifier($identifier)
    {
        $item = $this->getItemByIdentifier($identifier);
        
        $this->getItems()->removeElement($item);
        
        $this->updateAmounts();
    }
    
    public function updateItem($identifier, $data)
    {
        $item = $this->getItemByIdentifier($identifier);
        EntityHelper::setObjectFromArray($item, $data);
        
        $this->updateAmounts();
        
        return $item;
    }
    
    /**
     * 
     * @param unknown $title
     * @return OrderTax
     */
    public function getTaxByTitle($title)
    {
        $criteria = Criteria::create();
        
        $criteria
        ->where(Criteria::expr()->eq("title", $title))
        ->setMaxResults(1)
        ;
        
        return ($this->getTaxes()->matching($criteria)) ? $this->getTaxes()->matching($criteria)->current() : false;
    }
    
    /**
     * 
     * @param mixed $identifier
     * @return OrderItem
     */
    public function getItemByIdentifier($identifier)
    {
        $criteria = Criteria::create();
        
        $criteria
        ->where(Criteria::expr()->eq("identifier", $identifier))
        ->setMaxResults(1)
        ;
        
        return ($this->getItems()->matching($criteria)) ? $this->getItems()->matching($criteria)->current() : false;
    }
    
	/**
     * @return the $amountDiscounts
     */
    public function getAmountDiscounts ()
    {
        return $this->amountDiscounts;
    }

	/**
     * @return the $amountItemsSubTotal
     */
    public function getAmountItemsSubTotal ()
    {
        return $this->amountItemsSubTotal;
    }

	/**
     * @return the $amountPaid
     */
    public function getAmountPaid ()
    {
        return $this->amountPaid;
    }

	/**
     * @return the $amountRefunded
     */
    public function getAmountRefunded ()
    {
        return $this->amountRefunded;
    }

	/**
     * @return the $amountSubTotal
     */
    public function getAmountSubTotal ()
    {
        return $this->amountSubTotal;
    }

	/**
     * @return the $amountTaxes
     */
    public function getAmountTaxes ()
    {
        return $this->amountTaxes;
    }

	/**
     * @return the $amountTotal
     */
    public function getAmountTotal ()
    {
        return $this->amountTotal;
    }

	/**
     * @return the $billingAddress
     */
    public function getBillingAddress ()
    {
        return $this->billingAddress;
    }

	/**
     * @return the $cancelledAt
     */
    public function getCancelledAt ()
    {
        return $this->cancelledAt;
    }

	/**
     * @return the $closedAt
     */
    public function getClosedAt ()
    {
        return $this->closedAt;
    }

	/**
     * @return the $createdAt
     */
    public function getCreatedAt ()
    {
        return $this->createdAt;
    }

	/**
     * @return the $currency
     */
    public function getCurrency ()
    {
        return $this->currency;
    }

	/**
     * @return the $customer
     */
    public function getCustomer ()
    {
        return $this->customer;
    }

	/**
     * @return ArrayCollection
     */
    public function getDiscoundCodes ()
    {
        return $this->discoundCodes;
    }

	/**
     * @return the $email
     */
    public function getEmail ()
    {
        return $this->email;
    }

	/**
     * @return the $financialStatus
     */
    public function getFinancialStatus ()
    {
        return $this->financialStatus;
    }

	/**
     * @return the $fulfillments
     */
    public function getFulfillments ()
    {
        return $this->fulfillments;
    }

	/**
     * @return the $fulfillmentStatus
     */
    public function getFulfillmentStatus ()
    {
        return $this->fulfillmentStatus;
    }

	/**
     * @return the $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return ArrayCollection
     */
    public function getItems ()
    {
        return $this->items;
    }

	/**
     * @return the $returns
     */
    public function getReturns ()
    {
        return $this->returns;
    }

	/**
     * @return OrderShippingAddress $shippingAddress
     */
    public function getShippingAddress ()
    {
        return $this->shippingAddress;
    }

	/**
     * @return the $shippings
     */
    public function getShippings ()
    {
        return $this->shippings;
    }

	/**
     * @return ArrayCollection $taxes
     */
    public function getTaxes ()
    {
        return $this->taxes;
    }

	/**
     * @return the $taxesIncluded
     */
    public function getTaxesIncluded ()
    {
        return $this->taxesIncluded;
    }

	/**
     * @return the $transactions
     */
    public function getTransactions ()
    {
        return $this->transactions;
    }

	/**
     * @return the $updatedAt
     */
    public function getUpdatedAt ()
    {
        return $this->updatedAt;
    }

	/**
     * @param field_type $amountDiscounts
     */
    public function setAmountDiscounts ($amountDiscounts)
    {
        $this->amountDiscounts = $amountDiscounts;
        return $this;
    }

	/**
     * @param field_type $amountItemsSubTotal
     */
    public function setAmountItemsSubTotal ($amountItemsSubTotal)
    {
        $this->amountItemsSubTotal = $amountItemsSubTotal;
        return $this;
    }

	/**
     * @param field_type $amountPaid
     */
    public function setAmountPaid ($amountPaid)
    {
        $this->amountPaid = $amountPaid;
        return $this;
    }

	/**
     * @param field_type $amountRefunded
     */
    public function setAmountRefunded ($amountRefunded)
    {
        $this->amountRefunded = $amountRefunded;
        return $this;
    }

	/**
     * @param field_type $amountSubTotal
     */
    public function setAmountSubTotal ($amountSubTotal)
    {
        $this->amountSubTotal = $amountSubTotal;
        return $this;
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
     * @param field_type $amountTotal
     */
    public function setAmountTotal ($amountTotal)
    {
        $this->amountTotal = $amountTotal;
        return $this;
    }

	/**
     * @param field_type $billingAddress
     */
    public function setBillingAddress ($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

	/**
     * @param field_type $cancelledAt
     */
    public function setCancelledAt ($cancelledAt)
    {
        $this->cancelledAt = $cancelledAt;
        return $this;
    }

	/**
     * @param field_type $closedAt
     */
    public function setClosedAt ($closedAt)
    {
        $this->closedAt = $closedAt;
        return $this;
    }

	/**
     * @param field_type $createdAt
     */
    public function setCreatedAt ($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

	/**
     * @param field_type $currency
     */
    public function setCurrency ($currency)
    {
        $this->currency = $currency;
        return $this;
    }

	/**
     * @param field_type $customer
     */
    public function setCustomer ($customer)
    {
        $this->customer = $customer;
        return $this;
    }

	/**
     * @param field_type $discoundCodes
     */
    public function setDiscoundCodes ($discoundCodes)
    {
        $this->discoundCodes = $discoundCodes;
        return $this;
    }

	/**
     * @param field_type $email
     */
    public function setEmail ($email)
    {
        $this->email = $email;
        return $this;
    }

	/**
     * @param field_type $financialStatus
     */
    public function setFinancialStatus ($financialStatus)
    {
        $this->financialStatus = $financialStatus;
        return $this;
    }

	/**
     * @param field_type $fulfillments
     */
    public function setFulfillments ($fulfillments)
    {
        $this->fulfillments = $fulfillments;
        return $this;
    }

	/**
     * @param field_type $fulfillmentStatus
     */
    public function setFulfillmentStatus ($fulfillmentStatus)
    {
        $this->fulfillmentStatus = $fulfillmentStatus;
        return $this;
    }

	/**
     * @param field_type $id
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

	/**
     * @param field_type $items
     */
    public function setItems ($items)
    {
        $this->items = $items;
        return $this;
    }

	/**
     * @param field_type $returns
     */
    public function setReturns ($returns)
    {
        $this->returns = $returns;
        return $this;
    }

    public function editShippingAddress($data=array())
    {
        $address = $data;
        
        if(isset($data['region']))
        {
            if(isset($data['region']['taxRate']))
            {
                $rTaxRate =  new RegionTaxRate($data['region']['taxRate']);
            }
            
            $regionData = $data['region'];
            $regionData['taxRate'] = $rTaxRate;
            
            $address['region'] = new Region($regionData);
        }
        
        if(isset($data['country']))
        {
            if(isset($data['country']['taxRate']))
            {
                $cTaxRate =  new CountryTaxRate($data['country']['taxRate']);
            }
            
            $countryData = $data['country'];
            $countryData['taxRate'] = $cTaxRate;
            
            $address['country'] = new Country($countryData);
        }
        
        $shippingAddress = new OrderShippingAddress($address);
        
        $this->setShippingAddress($shippingAddress);
    }
    
	/**
     * @param OrderShipping $shippingAddress
     */
    public function setShippingAddress ($shippingAddress)
    {
        $shippingAddress->setOrder($this);
        $this->shippingAddress = $shippingAddress;
        
        $this->refreshTaxes();
        return $this;
    }

	/**
     * @param field_type $shippings
     */
    public function setShippings ($shippings)
    {
        $this->shippings = $shippings;
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
     * @param field_type $taxesIncluded
     */
    public function setTaxesIncluded ($taxesIncluded)
    {
        $this->taxesIncluded = $taxesIncluded;
        return $this;
    }

	/**
     * @param field_type $transactions
     */
    public function setTransactions ($transactions)
    {
        $this->transactions = $transactions;
        return $this;
    }

	/**
     * @param field_type $updatedAt
     */
    public function setUpdatedAt ($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
	/**
     * @return the $amountShipping
     */
    public function getAmountShipping ()
    {
        return $this->amountShipping;
    }

	/**
     * @param field_type $amountShipping
     */
    public function setAmountShipping ($amountShipping)
    {
        $this->amountShipping = $amountShipping;
        return $this;
    }


}