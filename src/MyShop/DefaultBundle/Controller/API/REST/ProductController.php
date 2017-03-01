<?php

namespace MyShop\DefaultBundle\Controller\API\REST;

use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function detailsAction($id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);

        /*$photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($idProduct);*/

        $productArray = [
            'model' => $product->getModel(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'comments' => $product->getComment(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y'),
            'category' => $product->getCategory()->getName(),
            'photo' => $photo->getPhotos()->getSmallFileName()
         ];

        $response = new JsonResponse(print_r($productArray));
        return $response;
    }

    public function photoAction()
    {

        $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto");

     	$productArr = $photo->createQueryBuilder('p')->getQuery()->getArrayResult();

        $response = new JsonResponse(print_r($productArr));
        return $response;
    }

    public function photoDetailsAction($idProduct)
    {

        $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($idProduct);


        // Выдаёт огромный список, вместо нужного массива...
        $productPhoto = [

        	'id' => $photo->getId(),
        	'title' => $photo->getTitle(),
            'name' => $photo->getSmallFileName(),
            'original_name' => $photo->getFileName(),
            'product' => $photo->getProduct() 

         ];

        $response = new JsonResponse(print_r($productPhoto));
        return $response;
    }

    public function listAction()
    {
        
        $products = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product");

        $productList = $products->createQueryBuilder('q')->getQuery()->getArrayResult();

        return new JsonResponse(print_r($productList) );
         
    }
    
}