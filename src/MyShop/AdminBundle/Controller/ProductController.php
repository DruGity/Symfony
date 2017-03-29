<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintViolationList;


class ProductController extends Controller
{	
	/**
     * @Template()
    */
	public function editAction(Request $request, $id)
	{
		$product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id); 

		$form = $this->createForm(ProductType::class, $product); 

        /******************************************/ 
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())  
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();               

                return $this->redirectToRoute("my_shop_admin.product_list"); 
            }
        }
        /******************************************/ 

        return [
            "form" => $form->createView(), 
            "product" => $product
        ];
	}

    public function listByCategoryAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return $this->render("@MyShopAdmin/Product/list.html.twig", [    
            "productList" => $productList   // что бы не создавать новую вьюшку
        ]);
    }

	/**
     * @Template()
    */
	public function listAction($page = 1)
	{      
		// $productList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->findAll();
        $query = $this->getDoctrine()->getManager()->createQuery("select p, c from MyShopDefaultBundle:Product p join p.category c");

        $paginator = $this->get("knp_paginator");
        $productList = $paginator->paginate($query, $page, 4);

		return ["productList" => $productList]; 
	}


	public function deleteAction($id)
    {   
        $manager = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        $photos = $product->getPhotos(); 
        $photoRemover = $this->get("myshop.product_photo_remover");

            foreach ($photos as $photo) {
            $photoRemover->removePhoto($photo);
            }

            $manager->remove($product);
            $manager->flush(); 

            /*$mail = $this->get("myshop_admin.sending_mail");
            $mail->sendEmail("Product" . " " . $product->getModel() . " " . "was deleted!");  */

            return $this->redirectToRoute("my_shop_admin.product_list");
    }
    
    /**
     * @Template()
    */
    public function addAction(Request $request) 
    {   

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/ 
        if ($request->isMethod("POST"))
        { 
            $form->handleRequest($request);
            
            if ($form->isSubmitted() )  
            {
                /** @var ConstraintViolationList $errorList */
                $errorList = $this->get('validator')->validate($product);
                if ($errorList->count() > 0)
                {
                    foreach ($errorList as $error) {
                        $this->addFlash('error', $error->getMessage());
                    }
                    return $this->redirectToRoute("my_shop_admin.product_add");
                }
                /*$filesAr = $request->files->get("myshop_defaultbundle_product"); // массив файлов который был загружен

                $photoFile = $filesAr["iconFile"]; //  обращение  к нужному файлу
                $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile);

                $product->setIconFileName($result->getSmallFileName());
                */              
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush(); 

                /*$mail = $this->get("myshop_admin.sending_mail");
                $mail->sendEmail("Product" . " " . $product->getModel() . " " . "was added!");  */

                return $this->redirectToRoute("my_shop_admin.product_list"); 
            }
        }
        /******************************************/

        return [
            "form" => $form->createView() 

        ];
    }
    
}    
