<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Template()
    */
    public function listAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->findAll();

        return ["categoryList" => $categoryList];
    }

    

    /**
     * @Template()
     */
     public function addAction(Request $request)
    {
        
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("my_shop_admin.category_list");
        }

        return ["form" => $form->createView()];

     }

    

    public function deleteAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category); 
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($category); // удаление из БД
        $manager->flush(); // выполнение

        return $this->redirectToRoute("my_shop_admin.category_list");

    }
    
    /**
     * @Template()
     */
    public function editAction(Request $request, $id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);

        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("my_shop_admin.category_list");
        }

        return [
        "form" => $form->createView(),
        "category" => $category
        ];

    }
}