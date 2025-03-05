<?php

namespace core\Modules\Cart\Handlers;

use core\Modules\Cart\Rules\CalculateTotalRule;
use core\Modules\Cart\Rules\ApplyDiscountRule;
use core\Modules\Cart\Presenters\ShowCartPresenter;
use core\Modules\Cart\Gateways\PaymentGateway;
use core\Modules\Cart\Gateways\InstallmentGateway;
use core\Modules\Cart\Repositories\CartRepositoryInterface;

class ShowCartHandler
{
    protected $calculateTotalRule;
    protected $applyDiscountRule;
    protected $presenter;
    protected $paymentGateway;
    protected $installmentGateway;
    protected $cartRepository;

    public function __construct(
        CalculateTotalRule $calculateTotalRule,
        ApplyDiscountRule $applyDiscountRule,
        ShowCartPresenter $presenter,
        PaymentGateway $paymentGateway,
        InstallmentGateway $installmentGateway,
        CartRepositoryInterface $cartRepository

    ) {
        $this->calculateTotalRule = $calculateTotalRule;
        $this->applyDiscountRule = $applyDiscountRule;
        $this->presenter = $presenter;
        $this->paymentGateway = $paymentGateway;
        $this->installmentGateway = $installmentGateway;
        $this->cartRepository = $cartRepository;
    }

    public function handle()
    {
        $cart = $this->cartRepository->getCart();

        $total = $this->calculateTotalRule->apply($cart);

        $finalValueDiscount = $this->applyDiscountRule->apply($total);

        $paymentMethods = $this->paymentGateway->getPaymentMethods();

        $installmentOptions = $this->installmentGateway->getInstallmentNumbers($total);

        return $this->presenter->present($cart, $total, $finalValueDiscount, $paymentMethods, $installmentOptions);
    }
}
