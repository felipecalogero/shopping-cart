<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Validators\AddProductValidator;
use core\Modules\Cart\Presenters\AddProductPresenter;
use core\Modules\Cart\Exceptions\ProductNotFoundException;
use core\Modules\Cart\Entities\Cart;
use Illuminate\Support\Facades\Session;

class AddProductHandler
{
    protected $validator;
    protected $presenter;

    public function __construct(
        AddProductValidator $validator,
        AddProductPresenter $presenter
    ) {
        $this->validator = $validator;
        $this->presenter = $presenter;
    }

    public function handle($product, $quantity = 1)
    {
        $this->validator->validate($product);

        $cart = Session::get('cart', []);

        $cart = Cart::addProduct($cart, $product, $quantity);
        Session::put('cart', $cart);

        return $this->presenter->present($cart);
    }
}
