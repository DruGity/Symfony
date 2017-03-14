<?php

namespace MyShop\DefaultBundle\Controller\API\REST;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ApiListController extends Controller
{
	/**
     * @Template()
    */
	public function listApiAction()
	{
		return[];
	}
}