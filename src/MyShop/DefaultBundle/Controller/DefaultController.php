<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{	
	/**
	* @Template()
	*/
    public function indexAction() // выводит шаблон index.html.twig
    {
        return [];
    }

    /**
	* @Template()
	*/
    public function showProductAction(Request $request, $id) // Вывод товаров из БД
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product"); // репозиторий в котором хранятся данные (Product.php)
        $product = $repository->find($id); // выбор id товара

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function showProductListAction()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $productList = $repository->findAll();

        return [
            "productList" => $productList
        ];
    }

    /**
     * @Template()
    */
    public function showCommentAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $comment = $repository->find($id);

        return [
            "comment" => $comment
        ];
    }

    /**
     * @Template()
    */
    public function showCategoryListAction()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $productList = $repository->findAll();

        return [
            "productList" => $productList
        ];
    }

   
}


