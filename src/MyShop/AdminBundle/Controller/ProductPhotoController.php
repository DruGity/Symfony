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

            $checkImgService = $this->get("myshop_admin.check_img_type");
            try {
                $checkImgService->check($photoFile);
            } catch (\InvalidArgumentException $ex) {
                die("Недопустимый тип картинки!");
            } 

            $nameGenerator = $this->get("myshop_admin.name_generator");

            $photoFileName = $product->getId() . $nameGenerator->generateName() . "." . $photoFile->getClientOriginalExtension();
            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/"; // путь сохранения фото

            $photoFile->move($photoDirPath, $photoFileName); // какому товару пренадлежит

            $img = new ImageResize($photoDirPath . $photoFileName); // создаём обьект с новой библиотеки
            $resize = $this->get("myshop_admin.image_resize");
           // $img = $resize->Resize(250, 200);
            $img->resizeToBestFit(250, 200); // указываем параметры новой картинки
            $smallPhotoName = "small_" . $photoFileName; // новый путь к картинки
            $img->save($photoDirPath . $smallPhotoName); // сохранение новой картинки

            $photo->setSmallFileName($smallPhotoName); // устанавливаем имя картинки в сеттер
            $photo->setFileName($photoFileName); // сохранение в БД
            $photo->setProduct($product); // сохранение в БД

            $manager->persist($photo); // проверка и выполнение
            $manager->flush(); //  и выполнение

            if ($form->isSubmitted())  // проверка на нажатие submit
            {
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

            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";
            $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id);
            $filename = $photoDirPath . $photo->getFileName();
            $smallFilename = $photoDirPath . $photo->getSmallFileName();


            if ($photo == null) {
            throw $this->createNotFoundException("Photo not found");
        }

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($photo);
            unlink($filename);
            unlink($smallFilename);
            $manager->flush();


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
