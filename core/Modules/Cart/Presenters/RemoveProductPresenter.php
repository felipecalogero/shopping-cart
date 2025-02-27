<?php

namespace core\Modules\Cart\Presenters;

class RemoveProductPresenter
{
    public function present($cart)
    {
        return [
            'message' => 'Produto removido do carrinho com sucesso!',
            'cart' => $cart
        ];
    }
}
