<?php

namespace MyShop\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyShopDefaultBundle:Default:index.html.twig');
    }

    public function showProductAction($name)
    {
    	return $this->render('MyShopDefaultBundle:Default:showProduct.html.twig', ["productName" => $name="Some Product"]);
    }

    public function createSomeProductAction()
    {
        $product = new Product();
        $product->setModel("J5");
        $product->setPrice(200);
        $product->setDescription("Great mobile phone");

        $doctine = $this->getDoctrine();
        $manager = $doctine->getManager();

        $manager->persist($product);
        $manager->flush();

        $response = new Response();
        $response->setContent($product->getId());
        return $response;
    }
}


