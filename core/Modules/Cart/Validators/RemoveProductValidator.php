<?php

namespace core\Modules\Cart\Validators;

use core\Modules\Cart\Exceptions\ProductNotInCartException;
use core\Modules\Cart\Repositories\CartRepositoryInterface;
use Illuminate\Support\Facades\Session;

class RemoveProductValidator
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function validate($productId)
    {
        $cart = $this->cartRepository->getCart();

        if (!isset($cart[$productId])) {
            throw new ProductNotInCartException('Product not found in cart.');
        }
    }
}
