<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function testAction(Request $request)
    {
        $response = new JsonResponse([
            "name" => "Aleksey",
            "Second Name" => "Chudak",
            "time" => time()
            ]);
        
        return $response;
    }

    /**
    * @Template()
    */
    public function indexAction(Request $request)
    {
        $request->setLocale('fr');
    }

    public function loadUsersAction()
    {
    	$this->get("pre_data_loader")->loadUsers();

    	/*$this->addFlash("Executed!", "Демо пользователь добавлен!");*/

    	return $this->redirectToRoute("my_shop_admin.product_list");
    }

    public function loadProductAction()
    {
    	$this->get("pre_data_loader")->loadProduct();

    	return $this->redirectToRoute("my_shop_admin.product_list");
    }

        public function loadCategoryAction()
    {
    	$this->get("pre_data_loader")->loadCategory();

    	return $this->redirectToRoute("my_shop_admin.category_list");
    }
}
