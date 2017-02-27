<?php

namespace MyShop\AdminBundle\Services;

use Eventviva\ImageResize;
use MyShop\AdminBundle\DTO\UploadedImageResult;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageService 
{
    
    private $checkImg;
    
    private $imageNameGenerator;

    private $uploadImageRootDir;

    private $photoFileName;

    private $smallPhotoName;


    public function __construct(CheckImgType $checkImg, $imageNameGenerator)
    {
        $this->checkImg = $checkImg;
        $this->imageNameGenerator = $imageNameGenerator;
    }

    public function setUploadImageRootDir($imageRootDir)
    {
        $this->uploadImageRootDir = $imageRootDir;
    }

    
    public function uploadImage(UploadedFile $uploadedFile, $productId = "" )
    {
        $imageNameGenerator = $this->imageNameGenerator;

        $photoFileName = $imageNameGenerator->generateName() . "." . $uploadedFile->getClientOriginalExtension();
        $photoDirPath = $this->uploadImageRootDir;

        $uploadedFile->move($photoDirPath, $photoFileName);

        $img = new ImageResize($photoDirPath . $photoFileName);
        $img->resizeToBestFit(250, 200);
        $smallPhotoName = "small_" . $photoFileName;
        $img->save($photoDirPath . $smallPhotoName);

        $result = new UploadedImageResult($smallPhotoName, $photoFileName); // возвращает с помощью DTO 

        return $result;
    }
}

