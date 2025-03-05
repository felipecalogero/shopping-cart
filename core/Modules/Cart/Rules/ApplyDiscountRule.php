<?php

namespace core\Modules\Cart\Rules;

use core\Modules\Cart\Repositories\CartRepositoryInterface;

class ApplyDiscountRule
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function apply($total)
    {
        $cart = $this->cartRepository->getCart();

        $paymentMethod = $cart['paymentMethod'] ?? 'pix';

        if ($paymentMethod === 'pix' || $paymentMethod === 'credito_vista') {
            $discountPercentage = 10;
            return $total - ($total * $discountPercentage / 100);
        }

        return $total;
    }
}
