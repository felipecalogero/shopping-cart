<?php

namespace core\Modules\Cart\Rules;

class ApplyInterestRateRule
{
    public function compoundInterestRate($months, $total)
    {
        $monthlyInterestRate = 0.01;

        $finalValue = round($total * pow((1 + $monthlyInterestRate), $months), 2);

        $installmentValue = $finalValue / $months;

        return [
            'installmentValue' => $installmentValue,
            'finalValue' => $finalValue,
        ];
    }
}
