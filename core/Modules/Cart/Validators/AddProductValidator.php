<?php

namespace core\Modules\Cart\Validators;

use core\Modules\Cart\Exceptions\ProductNotFoundException;

class AddProductValidator
{
    public function validate($product)
    {
        if (!$product) {
            throw new ProductNotFoundException('Product not found.');
        }
    }
}
