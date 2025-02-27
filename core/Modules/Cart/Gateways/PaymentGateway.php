<?php

namespace core\Modules\Cart\Gateways;

class PaymentGateway
{
    public function getPaymentMethods()
    {
        return [
            'pix' => 'Pix - 10% de desconto',
            'credito_vista' => 'Cartão de Crédito - 10% de desconto',
            'credito_parcelado' => 'Cartão Parcelado - Com juros'
        ];
    }
}
