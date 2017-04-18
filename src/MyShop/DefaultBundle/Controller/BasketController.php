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
/*        $products = $order->getProducts();
        $product = $products[0];*/

/*        $price = $products[0]->getPrice();
        $count = $products[0]->getCount();
        $sum = $products[0]->getSum();
        $product = $products[0];*/


/*        $a = $product;
        $b = array($a->getPrice());
        $c = array_sum($b);*/

        return [
        'order' => $order

        ];
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
            return $this->redirectToRoute("myshop.main_page");
        }
    }
}