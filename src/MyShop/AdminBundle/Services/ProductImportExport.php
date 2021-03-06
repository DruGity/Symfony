<?php

namespace MyShop\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MyShop\DefaultBundle\Entity\Product;

class ProductImportExport 
{
	
private $manager;

private $logger;

	public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function exportProducts()
    {
        $products = $this->manager->createQuery("select p from MyShopDefaultBundle:Product p")->getResult();

        $csv = "model,description,price" . "\n";

        /** @var Product $product */
        foreach ($products as $product)
        {
            $csv .= $product->getModel() . "," . $product->getDescription() . "," . $product->getPrice() . "\n";
        }

        return $csv;
    }

    public function parseCsvData($filePath, $clearProducts = false)
    {
        ini_set("auto_detect_line_endings", true);

        $fh = fopen($filePath, "r");
        if ($fh == null) {
            throw new \Exception("Can't open file!");
        }

        // set_time_limit(0);

        if ($clearProducts == true)
        {
            $this->manager->getConnection()->exec("SET foreign_key_checks = 0");
            $this->manager->getConnection()->exec("truncate product");
        }

        fgetcsv($fh);
        while ( ($data = fgetcsv($fh)) != FALSE )
        {
            if ($data[0] !== "" and $data[1] !== "" and $data[2] !== "") {
                $product = new Product();
                $product->setModel($data[0]);
                $product->setDescription($data[1]);
                $product->setPrice($data[2]);

                $this->manager->persist($product);
                $this->manager->flush();
            }
        }

        fclose($fh);
    }
}
