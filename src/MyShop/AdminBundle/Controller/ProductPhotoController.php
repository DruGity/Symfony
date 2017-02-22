<?php 

namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use \Eventviva\ImageResize;

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
        $manager = $this->getDoctrine()->getManager(); 
        $product = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct); 
        if ($product == null) { 
            return $this->createNotFoundException("Product not found!");  
        }

        $photo = new ProductPhoto(); 
        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST")) 
        {
            $form->handleRequest($request); // создание формы
            $filesAr = $request->files->get("myshop_defaultbundle_productphoto"); 

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];

            $checkImgService = $this->get("myshop_admin.check_img_type");
            try {
                $checkImgService->check($photoFile);
            } catch (\InvalidArgumentException $ex) {
                die("Недопустимый тип картинки!");
            } 

            $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile, $idProduct);

            $photo->setSmallFileName($result->getSmallFileName());
            $photo->setFileName($result->getBigFileName());
            $photo->setProduct($product); // сохранение в БД

            $manager->persist($photo); // проверка и выполнение
            $manager->flush(); //  и выполнение

            if ($form->isSubmitted())  // проверка на нажатие submit
            {
                $mail = $this->get("myshop_admin.sending_mail");
                $mail->sendEmail("The image was added to product" . " " . $product->getModel());  

                return $this->redirectToRoute("my_shop_admin.product_list"); // путь куда переносит после ввода данных
            }
            
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

        public function deleteAction($id)
        {   
            $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);

            if ($photo == null) {
            throw $this->createNotFoundException("Photo not found");
            }
            
            $photoRemover = $this->get("myshop.product_photo_remover");
            $photoRemover->removePhoto($photo);

            return $this->redirectToRoute("my_shop_admin.product_list");
    }

    /**
     * @Template()
    */
    public function editAction(Request $request, $id)
    {   

    $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);
    
    if ($photo == null) {
      throw $this->createNotFoundException("Photo not found");
    }

    $form = $this->createForm(ProductPhotoType::class, $photo); // ссылка на форму

    if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())  // проверка на нажатие submit
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($photo);
                $manager->flush();               // занос вводимых данных в базу

                return $this->redirectToRoute("my_shop_admin.product_list"); // путь куда переносит после ввода данных
            }

                
        }

    return [
            "form" => $form->createView(),
            "photo" => $photo
        ];

    }

}

