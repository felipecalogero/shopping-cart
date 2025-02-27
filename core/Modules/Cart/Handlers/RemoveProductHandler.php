<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Validators\RemoveProductValidator;
use core\Modules\Cart\Presenters\RemoveProductPresenter;
use core\Modules\Cart\Entities\Cart;
use Illuminate\Support\Facades\Session;

class RemoveProductHandler
{
    protected $validator;
    protected $presenter;

    public function __construct(
        RemoveProductValidator $validator,
        RemoveProductPresenter $presenter
    ) {
        $this->validator = $validator;
        $this->presenter = $presenter;
    }

    public function handle($productId)
    {
        $this->validator->validate($productId);

        $cart = Session::get('cart', []);
        $cart = Cart::removeProduct($cart, $productId);
        Session::put('cart', $cart);

        return $this->presenter->present($cart);
    }
}
