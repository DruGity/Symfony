<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Orders;
use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\OrdersProduct;
use MyShop\DefaultBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersController extends Controller
{
	/**
     * @Template()
    */
    public function indexAction()
    {
        $ordersList = $this->getDoctrine()->getRepository('MyShopDefaultBundle:Orders')->findAll();
/*        $customer = $this->getUser();
        $order = $this->getDoctrine()->getManager()->getRepository('MyShopDefaultBundle:Orders')->getOrCreateOrder($customer);
        $products = $order->getProducts();
        $products = $products[0];*/

        return [
        'ordersList' => $ordersList
        ];
    }
}