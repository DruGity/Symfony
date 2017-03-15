<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyShopAdminBundle:Default:index.html.twig');
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
