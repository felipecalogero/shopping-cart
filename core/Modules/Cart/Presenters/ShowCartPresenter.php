<?php

namespace core\Modules\Cart\Presenters;

class ShowCartPresenter
{
    public function present($cart, $total, $finalValueDiscount, $paymentMethods, $installmentOptions)
    {
        $formattedTotal = number_format($total, 2, ',', '.');
        $formattedFinalValue = number_format($finalValueDiscount, 2, ',', '.');
        $totalDiscount = $total - $finalValueDiscount;
        $formattedTotalDiscount = number_format($totalDiscount, 2, ',', '.');

        return [
            'cart' => $cart,
            'formattedTotal' => $formattedTotal,
            'formattedFinalValue' => $formattedFinalValue,
            'formattedTotalDiscount' => $formattedTotalDiscount,
            'paymentMethods' => $paymentMethods,
            'installmentOptions' => $installmentOptions,
        ];
    }
}
