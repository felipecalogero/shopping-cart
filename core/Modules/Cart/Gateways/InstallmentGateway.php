<?php

namespace core\Modules\Cart\Gateways;

use core\Modules\Cart\Rules\ApplyInterestRateRule;

class InstallmentGateway
{
    protected $applyInterestRateRule;

    public function __construct(ApplyInterestRateRule $applyInterestRateRule)
    {
        $this->applyInterestRateRule = $applyInterestRateRule;
    }

    public function getInstallmentNumbers($total)
    {
        $installments = [];

        for ($i = 2; $i <= 12; $i++) {
            $interestData = $this->applyInterestRateRule->compoundInterestRate($i, $total);

            $installments[$i] = [
                'label' => $i . 'x',
                'installmentValue' => number_format($interestData['installmentValue'], 2, ',', '.'),
                'totalWithInterest' => number_format($interestData['finalValue'], 2, ',', '.'),
            ];
        }

        return $installments;
    }
}
