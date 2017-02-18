<?php

namespace MyShop\AdminBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

class PhotoRemover extends Controller{

	private $manager;

	private $pathDir;

	public function __construct(EntityManager $em, $path) 
	{
		$this->manager = $em;
		$this->pathDir = $path;
	}


    public function removePhoto($photo)
    {
        $filename = $this->pathDir . $photo->getFileName();
        $smallFilename = $this->pathDir . $photo->getSmallFileName();

        unlink($filename);
        unlink($smallFilename);
    
        $this->manager->remove($photo);
        $this->manager->flush();   
    }
    
}

//
        
        //$manager = $this->getDoctrine()->getManager();
        //$this->getDoctrine() = $this->manager->remove($photo);



		
        