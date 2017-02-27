<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="model", type="string", length=255)
     *
     * @Assert\NotBlank(message="Поле модель не должно быть пустым")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage="Название модели слишком короткое, минимальное кол-во символов : {{ limit }}",
     *     maxMessage="Название модели слишком длинное. Максимальное кол-во символов : {{ limit }}"
     *)
     */
    private $model;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     *
     * @Assert\NotBlank(message="Введите цену цифровыми значениями!")
     * @Assert\Type(type="float", message="Вы должны ввести числовое значение!")
     * 
     * 
     *
     */
    private $price;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле описание не должно быть пустым")
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле комментарии не должно быть пустым")
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     *@var string
     *
     *@ORM\Column(name="icon_file_name", type="string", length=255 )
     */
    /*private $iconFileName;*/
    

    

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Category", inversedBy="productList") // указание связей: прямой и обратной
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id") // указание доп id для связи таблиц
    */
    private $category;

    

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\ProductPhoto", mappedBy="product", cascade={"All"})
    */
    private $photos;

    public function __construct()
    {
        $date = new \DateTime("now");
        $this->setDateCreatedAt($date);

        $this->photos = new ArrayCollection();
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreatedAt", type="datetime")
     */
    private $dateCreatedAt;

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
     * Set model
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set iconFileName
     *
     * @param string $iconFileName
     *
     * @return Product
     */
    public function setIconFileName($iconFileName)
    {
        $this->iconFileName = $iconFileName;

        return $this;
    }
    /**
     * Get iconFileName
     *
     * @return string
     */
    public function getIconFileName()
    {
        return $this->iconFileName;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Product
     */
    public function setComment($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comments;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return Product
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    /**
     * Get dateCreatedAt
     *
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Product
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add photo
     *
     * @param \MyShop\DefaultBundle\Entity\ProductPhoto $photo
     *
     * @return Product
     */
    public function addPhoto(\MyShop\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \MyShop\DefaultBundle\Entity\ProductPhoto $photo
     */
    public function removePhoto(\MyShop\DefaultBundle\Entity\ProductPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
