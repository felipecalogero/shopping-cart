<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Validators\RemoveProductValidator;
use core\Modules\Cart\Presenters\RemoveProductPresenter;
use core\Modules\Cart\Repositories\CartRepositoryInterface;

class RemoveProductHandler
{
    protected $validator;
    protected $presenter;
    protected $cartRepository;

    public function __construct(
        RemoveProductValidator $validator,
        RemoveProductPresenter $presenter,
        CartRepositoryInterface $cartRepository
    ) {
        $this->validator = $validator;
        $this->presenter = $presenter;
        $this->cartRepository = $cartRepository;
    }

    public function handle($productId)
    {
        $this->validator->validate($productId);

        $cart = $this->cartRepository->getCart();

        unset($cart[$productId]);

        $this->cartRepository->saveCart($cart);

        return $this->presenter->present($cart);
    }
}
