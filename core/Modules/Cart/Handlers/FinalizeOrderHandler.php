<?php

namespace core\Modules\Cart\Handlers;

use Illuminate\Http\Request;

class FinalizeOrderHandler
{
    public function handle(Request $request): array
    {
        $orderId = 'BSS-' . rand(10000, 99999);

        session()->forget('cart');

        return [
            'orderId' => $orderId,
        ];
    }
}
