<?php

namespace MyShop\DefaultBundle\PreData;

use Doctrine\Orm\EntityManager;
use MyShop\AdminBundle\Entity\User;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Entity\Category;

// Регистрировать как сервис
class PreDataLoader 
{
	private $manager;

	public function __construct(EntityManager $manager)
	{
		$this->manager =$manager;
	}
		// загрузка тестовый админов
	public function loadUsers()
	{
		$randNumber = rand();

		$user = new User();
		$user->setEmail($randNumber . "user@gmail.com ");
		$user->setPassword("9438" . $randNumber);
		$user->setUsername("abdul" . $randNumber);

		$this->manager->persist($user);
		$this->manager->flush();
	}
	       // загрузка тестовых товаров
	public function loadProduct()
	{
		$randNumber = rand();

		$product = new Product();
		$product->setModel("Model" . $randNumber);
		$product->setPrice($randNumber);
		$product->setDescription("description number" . $randNumber);
		$product->setIconFileName("icon" . $randNumber);
		$product->setComment("comment number" . $randNumber);
		$product->setCategory("category number" . $randNumber);
		$product->setDateCreatedAt("date number" . $randNumber);

		$this->manager->persist($product);
		$this->manager->flush();
	}

	public function loadCategory()
	{
		$randNumber = rand();

		$category = new Category();
		$category->setName("Name" . $randNumber);
		$category->setIconFileName("test" . $randNumber);

		$this->manager->persist($category);
		$this->manager->flush();
	}
}