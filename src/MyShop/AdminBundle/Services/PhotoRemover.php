<?php

namespace MyShop\AdminBundle\Services;

use Doctrine\ORM\EntityManager;

class PhotoRemover {

	private $manager;

	private $pathDir;

	public function __construct(EntityManager $em, $path) 
	{
		$this->manager = $em;
		$this->pathDir = $path;
	}


    public function removePhoto(ProductPhoto $photo)
    {
        $filename = $this->pathDir . $photo->getFileName();
        $smallFilename = $this->pathDir . $photo->getSmallFileName();

        unlink($filename);
        unlink($smallFilename);

        $man = $this->getDoctrine() -> $this->manager;
        $man->remove($photo);
        $man->flush();   
    }
    
}



			//$manager = $this->getDoctrine()->getManager();
            //$manager->remove($photo);
            
            //$manager->flush();