<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Repositories\CartRepositoryInterface;
use Illuminate\Http\Request;

class FinalizeOrderHandler
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function handle(Request $request): array
    {
        $orderId = 'BSS-' . rand(10000, 99999);

        $this->cartRepository->clearCart();

        return [
            'orderId' => $orderId,
        ];
    }
}
