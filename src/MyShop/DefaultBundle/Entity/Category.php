<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_file_name", type="string", length=255, nullable = true)
     */
    private $iconFileName;

    /**
     * @var ArrayCollection // тип массива
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\Product", mappedBy="category", cascade={"All"}) \\ вид связи и обратная связь
    */
    private $productList;

    public function __construct()
    {
        $this->productList = new ArrayCollection(); // конструктор для свойства $productList
    }

    public function addProduct(Product $product)
    {
        $product->setCategory($this);  // указываем, к какой категории пренадлежит товар
        $this->productList[] = $product; // добавление в массив еще одного элемента
    }

   

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set iconFileName
     *
     * @param mixed $iconFileName
     *
     */
    public function setIconFileName($iconFileName)
    {
        $this->iconFileName = $iconFileName;

        return $this;
    }

    /**
     * Get iconFileName
     *
     * @return mixed
     */
    public function getIconFileName()
    {
        return $this->iconFileName;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductList()
    {
        return $this->productList;
    }

    /**
     * @param mixed Product
     */
    public function setProductList(Product $productList)
    {
        $this->productList = $productList;
    }
}

