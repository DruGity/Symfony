<?php

namespace MyShop\AdminBundle\DTO; // data transfer objects


class UploadedImageResult
{
    private $smallFileName;

    private $bigFileName;

    public function __construct($smallFileName, $bigFileName)
    {
        $this->smallFileName = $smallFileName;
        $this->bigFileName = $bigFileName;
    }

    /**
     * @return string
     */
    public function getSmallFileName()
    {
        return $this->smallFileName;
    }

    /**
     * @return string
     */
    public function getBigFileName()
    {
        return $this->bigFileName;
    }
}