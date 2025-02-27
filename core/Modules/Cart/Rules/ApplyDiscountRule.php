<?php

namespace core\Modules\Cart\Rules;

use Illuminate\Support\Facades\Session;

class ApplyDiscountRule
{
    public function apply($total)
    {
        $paymentMethod = Session::get('paymentMethod', 'pix');

        if ($paymentMethod === 'pix' || $paymentMethod === 'credito_vista') {
            $discountPercentage = 10;
            return $total - ($total * $discountPercentage / 100);
        }

        return $total;
    }
}
