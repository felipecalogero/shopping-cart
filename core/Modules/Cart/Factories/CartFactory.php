<?php

namespace core\Modules\Cart\Factories;

use core\Modules\Cart\Entities\Cart;

class CartFactory
{
    public static function create(): Cart
    {
        return new Cart();
    }
}
