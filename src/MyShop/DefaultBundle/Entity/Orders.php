<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\OrdersRepository")
 */
class Orders
{
    const STATUS_OPEN = 1;
    const STATUS_DONE = 2;
    const STATUS_REJECT = 3;
    const STATUS_CONFIRMED = 1;
    const STATUS_UNCONFIRMED = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreatedAt", type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="confirm", type="integer")
     */
    private $confirm;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
     */
    private $phoneNumber;

   /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\OrdersProduct", mappedBy="order", cascade={"all"})
    */
    private $products;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="id_customer", referencedColumnName="id")
    */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_type", type="string", length=255, nullable=true)
     */
    private $deliveryType;




    public function __construct()
    {
        $this->setDateCreatedAt(new \DateTime("now"));
        $this->setStatus(self::STATUS_OPEN);
        $this->setConfirmStatus(self::STATUS_UNCONFIRMED);
        $this->products = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return CustomerOrder
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    /**
     * Get dateCreatedAt
     *
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }

    /**
     * Set confirm
     *
     * @param integer $confirm
     *
     * @return Orders
     */
    public function setConfirmStatus($confirm)
    {
        $this->confirm = $confirm;

        return $this;
    }

    /**
     * Get confirm
     *
     * @return integer
     */
    public function getConfirmStatus()
    {
        return $this->confirm;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add product
     *
     * @param \MyShop\DefaultBundle\Entity\OrdersProduct $product
     *
     * @return Orders
     */
    public function addProduct(\MyShop\DefaultBundle\Entity\OrdersProduct $product)
    {
        $product->setOrder($this);
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \MyShop\DefaultBundle\Entity\OrdersProduct $product
     */
    public function removeProduct(\MyShop\DefaultBundle\Entity\OrdersProduct $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function getSum()
    {
        return $this->price * $this->count;
    }

    public function getTotalPrice()
    {
        /** @var OrderProduct $product */
        $total = 0;
        foreach ($this->products as $product) {
            $total = $total + ( $product->getPrice() * $product->getCount());
        }

        return $total;
    }

    public function getTotalCount()
    {
        $totalCount = 0;
        foreach ($this->products as $product) {
            $totalCount = $totalCount + $product->getCount();
        }

        return $totalCount;
    }

    /**
     * Set customer
     *
     * @param \MyShop\DefaultBundle\Entity\Customer $customer
     *
     * @return Orders
     */
    public function setCustomer(\MyShop\DefaultBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \MyShop\DefaultBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set deliveryType
     *
     * @param string $deliveryType
     *
     * @return Orders
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * Get deliveryType
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }
}
