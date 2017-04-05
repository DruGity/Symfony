<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use GuzzleHttp\Client;

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

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $product = $repository->find($id); // выбор id товара

        if ($product == null) {
            throw new NotFoundHttpException();
            
        }

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function showProductListAction($page = 1)
    {

        $query = $this->getDoctrine()->getManager()->createQuery("select p, c from MyShopDefaultBundle:Product p join p.category c");
        $paginator = $this->get("knp_paginator");
        $productList = $paginator->paginate($query, $page, 4);

        /*$dql = 'select p from MyShopDefaultBundle:Product p order by p.price desc';
        $productList = $this->getDoctrine()->getManager()->createQuery($dql)->getResult();*/ // пример сортировки asc desc
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
        $categoryList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->findAll();

        return ["categoryList" => $categoryList];
    }

    public function listByCategoryAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return $this->render("@MyShopDefault/Default/showProductList.html.twig", [    
            "productList" => $productList   // что бы не создавать новую вьюшку
        ]);
    }
        //API GUZZLE CLIENT 
    public function guzzleClientAction() 
    {
        $client = new Client();
        $response = $client->request("POST", "http://127.0.0.1:8000/api/products");
        var_dump($response);
    }
}


