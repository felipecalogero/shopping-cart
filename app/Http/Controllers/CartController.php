<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'message' => 'Produto adicionado ao carrinho com sucesso!',
            'totalQuantity' => $totalQuantity
        ]);
    }

    public function showCart()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);
        $formattedTotal = number_format($total, 2, ',', '.');

        $finalValueDiscount = $this->applyDiscount($total);
        $formattedFinalValue = number_format($finalValueDiscount, 2, ',', '.');

        $totalDiscount = $total - $finalValueDiscount;
        $formattedTotalDiscount = number_format($totalDiscount, 2, ',', '.');

        $paymentMethods = $this->getPaymentMethods();
        $installmentNumber = $this->getInstallmentNumbers();

        $installmentOptions = [];
        foreach ($installmentNumber as $key => $parcelas) {
            $result = $this->compoundInterestRate($key, $total);

            $installmentOptions[$key] = [
                'label' => $parcelas,
                'installmentValue' => number_format($result['installmentValue'], 2, ',', '.'),
                'totalWithInterest' => number_format($result['finalValue'], 2, ',', '.'),
            ];
        }

        return view('cart.index', compact('cart', 'formattedTotal', 'formattedFinalValue', 'formattedTotalDiscount', 'paymentMethods', 'installmentOptions'));
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function applyDiscount($subTotal = 0)
    {
        $paymentMethod = session()->get('paymentMethod', 'credito_vista');

        if ($paymentMethod === 'pix' || $paymentMethod === 'credito_vista') {
            $discountPercentage = 10;
            $total = $subTotal - ($subTotal * $discountPercentage / 100);
        }

        return $total ?? 0;
    }

    private function getPaymentMethods()
    {
        return [
            'pix' => 'Pix - 10% de desconto',
            'credito_vista' => 'Crédito à vista - 10% de desconto',
            'credito_parcelado' => 'Crédito parcelado - Com juros'
        ];
    }

    private function getInstallmentNumbers()
    {
        return [
            '2' => '2x',
            '3' => '3x',
            '4' => '4x',
            '5' => '5x',
            '6' => '6x',
            '7' => '7x',
            '8' => '8x',
            '9' => '9x',
            '10' => '10x',
            '11' => '11x',
            '12' => '12x'
        ];
    }

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

    public function removeFromCart(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Produto removido do carrinho com sucesso!');
    }

    public function finalOrder(Request $request)
    {
        $orderId = 'BSS-' . rand(10000, 99999);

        session()->forget('cart');

        return view('cart.checkout', compact('orderId'));
    }

}
