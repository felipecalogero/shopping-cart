<?php

namespace core\Modules\Cart\Rules;

class CalculateTotalRule
{
    public function apply($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
