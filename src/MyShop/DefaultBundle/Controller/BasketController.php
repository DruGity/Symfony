<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\Orders;
use MyShop\DefaultBundle\Entity\OrdersProduct;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\OrdersType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasketController extends Controller
{
    /**
     * @Template()
    */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:Orders')->getOrCreateOrder($customer);

        return [
        'order' => $order

        ];
    }

    /**
     * @Template()
    */
    public function historyOrderAction()
    {
        $customer = $this->getUser();
        $orders = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Orders")->findBy(["customer" => $customer]);
        return ['orders' => $orders];
    }

    /**
    * @Template()
    */
   public function orderProductsAction($id)
   {
       $order = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Orders")->find($id);
       if ($order == null) {
           throw $this->createNotFoundException();
       }
       return ['order' => $order];
   }

   public function removeProductFromBasketAction($id)
    {   $orderProduct = $this->getDoctrine()->getRepository("MyShopDefaultBundle:OrdersProduct")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($orderProduct);
        $manager->flush();

        return $this->redirectToRoute("myshop.order_confirm");
    }
    public function recalculationCurrentOrderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:Orders')->getOrCreateOrder($customer);

        $products = $order->getProducts();
        /** @var OrderProduct $product */
        foreach ($products as $product)
        {
            $key = "prod_" . $product->getId();
            $productCount = $request->get($key);
            $productCount = intval($productCount);

            if ($productCount < 0) {
                $product->setCount(1);
            }
            elseif ($productCount == 0) {
                $this->removeProductFromBasketAction($product);
            }
            else {
                $product->setCount($productCount);
            }
        }

        $manager->persist($order);
        $manager->flush();
        
        return $this->redirectToRoute("myshop.order_confirm");
    }

    /**
     * @Template()
    */
    public function confirmAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:Orders')->getOrCreateOrder($customer);

        $form = $this->createForm(OrdersType::class, $order);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $order->setStatus(Orders::STATUS_DONE);
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute("myshop.product_list");
        }

        return [
            'form' => $form->createView(),
            'order' => $order
        ];
    }

    public function addProductToBasketAction($idProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:Orders')->getOrCreateOrder($customer);

        $dql = 'select p from MyShopDefaultBundle:OrdersProduct p where p.order = :orderCustomer and p.idProduct = :idProduct';

        $productOrder = $manager->createQuery($dql)->setParameters([
            'idProduct' => $idProduct,
            'orderCustomer' => $order
        ])->getOneOrNullResult();

        if ($productOrder !== null)
        {
            $count = $productOrder->getCount();
            $productOrder->setCount($count + 1);

            $manager->persist($productOrder);
            $manager->flush();
            return $this->redirectToRoute("myshop.product_list");
        }
        else {
            $productShop = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct);

            $productOrder = new OrdersProduct();
            $productOrder->setCount(1);
            $productOrder->setModel($productShop->getModel());
            $productOrder->setPrice($productShop->getPrice());
            $productOrder->setIdProduct($productShop->getId());
            $productOrder->setOrder($order);

            $manager->persist($productOrder);
            $manager->flush();
            return $this->redirectToRoute("myshop.product_list");
        }
    }
}