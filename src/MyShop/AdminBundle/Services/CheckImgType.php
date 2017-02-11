<?php

namespace MyShop\AdminBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

/*
    array (size=2)
      0 =>
        array (size=2)
          0 => string 'jpg' (length=3)
          1 => string 'image/jpg' (length=9)
      1 =>                                // структура массива который заполняет конструктор с помощью конфига
        array (size=2)
          0 => string 'gif' (length=3)
          1 => string 'image/gif' (length=9)
      */
class CheckImgType
{
    private $supportImageTypeList;

    public function __construct($imageTypeList) // вынос типов картинок в конфиг
    {
        $this->supportImageTypeList = $imageTypeList;
    }

    public function check(UploadedFile $photoFile)
    {
        $checkTrue = false;
        $mimeType = $photoFile->getClientMimeType();
        foreach ($this->supportImageTypeList as $imgType) {
            if ($mimeType == $imgType[1]) { // проверка mimetype файла например: image/jpg
                $checkTrue = true;
            }
        }
        if ($checkTrue !== true) {
            throw new \InvalidArgumentException("Неверный Mymetype!");
        }

        $fileExt = $photoFile->getClientOriginalExtension();
        $checkTrue = false;
        foreach ($this->supportImageTypeList as $imgType) {
            if ($fileExt == $imgType[0]) {  // проверка расширения файла например:jpg
                $checkTrue = true;
            }
        }

        if ($checkTrue == false) {
            throw new \InvalidArgumentException("Ошибка!");
        }

        

    }
    
}