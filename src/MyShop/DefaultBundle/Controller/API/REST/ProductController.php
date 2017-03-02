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

        $productArray = [
            'model' => $product->getModel(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'comments' => $product->getComment(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y'),
            'category' => $product->getCategory()->getName()
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

        $photos = $product->getPhotos(); // массив

         $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";

        $productPhoto = [

        	'id' => $photos[0]->getId(),
        	'title' => $photos[0]->getTitle(),
        	'file_name' => $photos[0]->getFileName(),
        	'small_file_name' =>$photoDirPath . $photos[0]->getSmallFileName(),
        	'image_url' => $_SERVER["HTTP_HOST"] . '/' . "photos" . '/' .$photos[0]->getSmallFileName()

         ];

         echo var_dump($_SERVER["HTTP_HOST"]);

        $response = new JsonResponse(print_r($productPhoto));
        return $response;
    }

	 public function listAction()
    {
        
        $products = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product");

        $products->createQueryBuilder('q')->getQuery()->getArrayResult();

        return new JsonResponse($productList);
         
    }    
    
}