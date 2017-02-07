<?php
namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductPhotoController extends Controller
{

	/**
     * @Template()
    */
    public function listAction($idProduct)
    {
        $product = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Product")->find($idProduct);

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function addAction(Request $request, $idProduct)
    {
        $manager = $this->getDoctrine()->getManager(); // вытаскивает товар из БД
        $product = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct); // вытаскивает нужный id товара
        if ($product == null) { // проверка на его существование
            return $this->createNotFoundException("Product not found!");  
        }

        $photo = new ProductPhoto(); // создание нового обьекта
        $form = $this->createForm(ProductPhotoType::class, $photo); // создание формы

        if ($request->isMethod("POST")) // обработка пост метода
        {
            $form->handleRequest($request); // создание формы

            $filesAr = $request->files->get("myshop_defaultbundle_productphoto"); // Префикс с формы

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];
            $mimeType = $photoFile->getClientMimeType();
            if ($mimeType !== "image/jpeg" and $mimeType !== "image/jpg" and $mimeType !== "image/gif" and $mimeType !== "image/png") {
                throw new InvalidArgumentException("MimeType is blocked!");
            }

            $fileExt = $photoFile->getClientOriginalExtension();
            if ($fileExt !== "jpg" and $fileExt !== "png" and $fileExt !== "gif") {
                throw new InvalidArgumentException("Extension is blocked!");
            }

            $photoFileName = $product->getId() . rand(1000000, 9999999) . "." . $photoFile->getClientOriginalExtension();
            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/"; // путь сохранения фото

            $photoFile->move($photoDirPath, $photoFileName); // какому товару пренадлежит

            $photo->setFileName($photoFileName); // сохранение в БД
            $photo->setProduct($product); // сохранение в БД

            $manager->persist($photo); // проверка и выполнение
            $manager->flush(); //  и выполнение
        }

        return [
            "form" => $form->createView(),
            "product" => $product,
        ];
    }

    public function deleteAction($id)
    {

    $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);

    if ($photo == null) {
      throw $this->createNotFoundException("Photo not found");
	}

    $manager = $this->getDoctrine()->getManager();
    $manager->remove($photo);
    $manager->flush();


    return $this->redirectToRoute("my_shop_admin.product_list");
    }

    /**
     * @Template()
    */
    public function editAction($id)
    {	

    $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);
    
    if ($photo == null) {
      throw $this->createNotFoundException("Photo not found");
	}

    $form = $this->createForm(ProductPhotoType::class, $photo);

    return [
            "form" => $form->createView()
        ];

    }

}

