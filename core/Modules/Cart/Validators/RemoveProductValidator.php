<?php

namespace core\Modules\Cart\Validators;

use core\Modules\Cart\Exceptions\ProductNotInCartException;
use Illuminate\Support\Facades\Session;

class RemoveProductValidator
{
    public function validate($productId)
    {
        $cart = Session::get('cart', []);

        if (!isset($cart[$productId])) {
            throw new ProductNotInCartException('Product not found in cart.');
        }
    }
}
