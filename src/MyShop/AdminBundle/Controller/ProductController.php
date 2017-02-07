<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request; // указать при использовании request

class ProductController extends Controller
{	
	/**
     * @Template()
    */
	public function editAction(Request $request, $id)
	{
		$product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id); // определение по id с БД

		$form = $this->createForm(ProductType::class, $product); // всё тоже самое что и в добавлении

        /******************************************/ //обработка метода POST
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())  // проверка на нажатие submit
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();               // занос вводимых данных в базу

                return $this->redirectToRoute("my_shop_admin.product_list"); // путь куда переносит после ввода данных
            }
        }
        /******************************************/ // окончание обработки метода ПОСТ

        return [
            "form" => $form->createView(), // возврат формы
            "product" => $product
        ];
	}

    public function listByCategoryAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return $this->render("@MyShopAdmin/Product/list.html.twig", [    // вызов того же шаблона что и у listAction() 
            "productList" => $productList   // что бы не создавать новую вьюшку
        ]);
    }

	/**
     * @Template()
    */
	public function listAction()
	{
		$productList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->findAll();
		return ["productList" => $productList]; // не работает
	}

	public function deleteAction($id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id); 
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product); // удаление из БД
        $manager->flush(); // выполнение

        return $this->redirectToRoute("my_shop_admin.product_list");
    }

   /**
     * @Template()
    */
    public function addAction(Request $request) //
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/ //обработка метода POST
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())  // проверка на нажатие submit
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();               // занос вводимых данных в базу

                return $this->redirectToRoute("my_shop_admin.product_list"); // путь куда переносит после ввода данных
            }
        }
        /******************************************/

        return [
            "form" => $form->createView() // возврат формы

        ];
    }

    
}    
