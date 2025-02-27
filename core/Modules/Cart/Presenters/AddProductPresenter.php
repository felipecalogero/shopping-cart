<?php

namespace core\Modules\Cart\Presenters;

class AddProductPresenter
{
    public function present($cart)
    {
        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return [
            'message' => 'Product added to cart successfully!',
            'totalQuantity' => $totalQuantity
        ];
    }
}
