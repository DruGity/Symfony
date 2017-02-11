<?php

namespace MyShop\AdminBundle\Services;

use \Eventviva\ImageResize;

class Resizing
{
    

    public function Resize($a, $b)
    {
    	
        return resizeToBestFit($a, $b);

    }
    
}