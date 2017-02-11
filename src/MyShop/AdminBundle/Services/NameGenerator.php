<?php

namespace MyShop\AdminBundle\Services;

class NameGenerator
{
    public function generateName()
    {
        return rand(100000000, 999999999);
    }
}

