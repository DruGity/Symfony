<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{	
	
	public function showCategoryAction()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Category"); // репозиторий в котором хранятся данные (Category.php)
        $category = $repository->find($id); // выбор id товара

        return [
            "category" => $category
        ];
    }

    

}