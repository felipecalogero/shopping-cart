<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Factories\CartFactory;
use core\Modules\Cart\Repositories\CartRepositoryInterface;
use core\Modules\Cart\Entities\Cart;
use core\Modules\Cart\Presenters\AddProductPresenter;
use core\Modules\Cart\Validators\AddProductValidator;

class AddProductHandler
{
    protected $validator;
    protected $presenter;
    protected $cartRepository;

    public function __construct(
        AddProductValidator $validator,
        AddProductPresenter $presenter,
        CartRepositoryInterface $cartRepository
    ) {
        $this->validator = $validator;
        $this->presenter = $presenter;
        $this->cartRepository = $cartRepository;
    }

    public function handle($product, $quantity = 1)
    {
        $this->validator->validate($product);

        $cartItems = $this->cartRepository->getCart();

        $cart = CartFactory::create();
        $cart->setItems($cartItems);

        $cart->addProduct($product, $quantity);

        $this->cartRepository->saveCart($cart->getItems());

        return $this->presenter->present($cart->getItems());
    }
}
