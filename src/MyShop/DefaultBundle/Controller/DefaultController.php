<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{	
	/**
	* @Template()
	*/
    public function indexAction() // выводит шаблон index.html.twig
    {
        return [];
    }

    

    


    public function createSomeProductAction() // добавляет товар в базу
    {
        $product = new Product();
        $product->setModel("M3");
        $product->setPrice(450.99);
        $product->setDescription("High quality product");
        $product->setComment("Здравствуйте.
Сегодня получил телефон. Вместе с ним руководство, телефоны поддержки, гарантийный талон от магазина, товарный чек. Гарантийного талона от самого производителя нет. Или его и не должно было быть? На планшет у меня в сервисном центре его требовали. Теперь беспокоюсь, как с телефоном быть.");

        $doctine = $this->getDoctrine(); // обращение к базе
        $manager = $doctine->getManager(); // системная функция

        $manager->persist($product); // подготовка
        $manager->flush(); // выполнение

        $response = new Response(); // ??
        $response->setContent($product->getId()); // вытаскиваем id из таблицы
        return $response;
    }

    /**
	* @Template()
	*/
    public function showProductAction(Request $request, $id) // Вывод товаров из БД
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product"); // репозиторий в котором хранятся данные (Product.php)
        $product = $repository->find($id); // выбор id товара

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function showProductListAction()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $productList = $repository->findAll();

        return [
            "productList" => $productList
        ];
    }

    /**
     * @Template()
    */
    public function showCommentAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $comment = $repository->find($id);

        return [
            "comment" => $comment
        ];
    }

   
}


