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


class ProductController extends Controller
{	
	/**
     * @Template()
    */
	public function editAction(Request $request, $id)
	{
		$product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id); 

		$form = $this->createForm(ProductType::class, $product); 

        /******************************************/ //обработка метода POST
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
        /******************************************/ // окончание обработки метода ПОСТ

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
	public function listAction()
	{      
		$productList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->findAll();
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

            $mail = $this->get("myshop_admin.sending_mail");
            $mail->sendEmail("Product" . " " . $product->getModel() . " " . "was deleted!");  

            return $this->redirectToRoute("my_shop_admin.product_list");
    }
    
    /**
     * @Template()
    */
    public function addAction(Request $request) 
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/ //обработка метода POST
        if ($request->isMethod("POST"))
        { 
            if ($form->isSubmitted())  
            {
                $filesAr = $request->files->get("myshop_defaultbundle_product");

                /* @var UploadedFile $photoFile */
                $photoFile = $filesAr["icon_file_name"];
                $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile, $idProduct);

                $product->setIconFileName($result->getSmallFileName());
                $product->setProduct($product);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush(); 

                $mail = $this->get("myshop_admin.sending_mail");
                $mail->sendEmail("Product" . " " . $product->getModel() . " " . "was added!");  

                return $this->redirectToRoute("my_shop_admin.product_list"); 
            }
        }
        /******************************************/

        return [
            "form" => $form->createView() // возврат формы

        ];
    }

    
}    
