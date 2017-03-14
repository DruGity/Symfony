<?php

namespace MyShop\DefaultBundle\PreData;

use Doctrine\Orm\EntityManager;
use MyShop\AdminBundle\Entity\User;
use MyShop\DefaultBundle\Entity\Product;

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

	public function loadProduct()
	{
		$randNumber = rand();

		$user = new Product();
		$user->setModel("Model" . $randNumber);
		$user->setPrice($randNumber);
		$user->setDescription("description number" . $randNumber);
		$user->setIconFileName("icon" . $randNumber);
		$user->setComment("comment number" . $randNumber);
		$user->setCategory("category number" . $randNumber);
		$user->setName("product" . $randNumber);

		$this->manager->persist($user);
		$this->manager->flush();
	}
}