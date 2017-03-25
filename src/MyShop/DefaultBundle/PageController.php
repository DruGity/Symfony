<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller  // отображение созданных страниц
{
    /**
     * @Template()
    */
    public function indexAction($pageKey) // страницы отображается по ключу
    {
        $page = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Page")->findOneBy(["pageKey" => $pageKey]);
        if ($page == null) {
            throw $this->createNotFoundException();
        }

        return ['page' => $page];
    }
}
