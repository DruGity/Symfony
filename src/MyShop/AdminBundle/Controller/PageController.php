<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Page;
use MyShop\DefaultBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;


class PageController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $pageList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Page")->findAll();

        return ["pageList" => $pageList];
    }

    /**
     * @Template()
    */
    public function addAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

               if ($form->isSubmitted() )  
            {
                /** @var ConstraintViolationList $errorList */
                $errorList = $this->get('validator')->validate($page);
                    if ($errorList->count() > 0)
                    {
                        foreach ($errorList as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }
                        return $this->redirectToRoute("my_shop_admin.page_add");
                }
         
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($page);
                    $manager->flush(); 

                    $this->addFlash("success", "Страница успешно добавлена!");

                    return $this->redirectToRoute("my_shop_admin.page_list"); 
            }      
            
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $page = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Page")->find($id);
        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();

            $this->addFlash("success", "Страница успешно сохранена!");

            return $this->redirectToRoute("my_shop_admin.page_list");
        }

        return ['form' => $form->createView(), 'page' => $page];
    }

    public function deleteAction($id)
    {   
        $manager = $this->getDoctrine()->getManager();
        $page = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Page")->find($id);

        $manager->remove($page);
        $manager->flush(); 

            /*$mail = $this->get("myshop_admin.sending_mail");
            $mail->sendEmail("Product" . " " . $product->getModel() . " " . "was deleted!");  */

        return $this->redirectToRoute("my_shop_admin.page_list");
    }

}