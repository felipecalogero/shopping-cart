<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Rules\CalculateTotalRule;
use core\Modules\Cart\Rules\ApplyDiscountRule;
use core\Modules\Cart\Presenters\ShowCartPresenter;
use core\Modules\Cart\Gateways\PaymentGateway;
use core\Modules\Cart\Gateways\InstallmentGateway;
use Illuminate\Support\Facades\Session;

class ShowCartHandler
{
    protected $calculateTotalRule;
    protected $applyDiscountRule;
    protected $presenter;
    protected $paymentGateway;
    protected $installmentGateway;

    public function __construct(
        CalculateTotalRule $calculateTotalRule,
        ApplyDiscountRule $applyDiscountRule,
        ShowCartPresenter $presenter,
        PaymentGateway $paymentGateway,
        InstallmentGateway $installmentGateway
    ) {
        $this->calculateTotalRule = $calculateTotalRule;
        $this->applyDiscountRule = $applyDiscountRule;
        $this->presenter = $presenter;
        $this->paymentGateway = $paymentGateway;
        $this->installmentGateway = $installmentGateway;
    }

    public function handle()
    {
        $cart = Session::get('cart', []);

        $total = $this->calculateTotalRule->apply($cart);
        $finalValueDiscount = $this->applyDiscountRule->apply($total);
        $paymentMethods = $this->paymentGateway->getPaymentMethods();
        $installmentOptions = $this->installmentGateway->getInstallmentNumbers($total);

        return $this->presenter->present($cart, $total, $finalValueDiscount, $paymentMethods, $installmentOptions);
    }
}
