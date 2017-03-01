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

        $response = new JsonResponse($productArray);
        return $response;
    }

    public function photoAction()
    {

        $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto");

     	$productArr = $photo->createQueryBuilder('p')->getQuery()->getArrayResult();

        $response = new JsonResponse($productArr);
        return $response;
    }

    public function photoDetailsAction($idProduct)
    {

        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($idProduct);

        $photos = $product->getPhotos();


        // Выдаёт огромный список, вместо нужного массива...
        $productPhoto = [

        	'id' => $photos->getId()
        	

         ];

        $response = new JsonResponse($productPhoto);
        return $response;
    }

    public function listAction()
    {
        
        $products = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product");

        $productList = $products->createQueryBuilder('q')->getQuery()->getArrayResult();

        return new JsonResponse($productList);
         
    }
    
}