<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;

class CartControllerTest extends TestCase
{
    public function test_show_cart_displays_correct_totals()
    {
        // Scene 1
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 50, 'quantity' => 2],
            2 => ['name' => 'Produto 2', 'price' => 30, 'quantity' => 1],
        ];

        session(['cart' => $cart]);

        $total = (50 * 2) + 30;

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedTotal', number_format($total, 2, ',', '.'));

        // Scene 2
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 50, 'quantity' => 2],
            3 => ['name' => 'Produto 3', 'price' => 30, 'quantity' => 1],
            5 => ['name' => 'Produto 5', 'price' => 48, 'quantity' => 3],
        ];

        session(['cart' => $cart]);

        $total = (50 * 2) + 30 + (48 * 3);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedTotal', number_format($total, 2, ',', '.'));

        // Scene 3 (empty cart)
        session(['cart' => []]);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedTotal', '0,00');
    }

    public function test_add_to_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Produto Teste',
            'price' => 100.00,
        ]);

        // Scene 1
        $response = $this->post(route('cart.add', $product->id));

        $response->assertStatus(200);
        $cart = session('cart');
        $this->assertNotEmpty($cart);
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]['quantity']);

        // Scene 2 (Product already exists in cart)
        $response = $this->post(route('cart.add', $product->id));

        $cart = session('cart');
        $this->assertEquals(2, $cart[$product->id]['quantity']);
    }

    public function test_remove_from_cart()
    {
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 50, 'quantity' => 1],
            2 => ['name' => 'Produto 2', 'price' => 30, 'quantity' => 1],
        ];

        session(['cart' => $cart]);

        // Scene 1 (remove specify item)
        $response = $this->get(route('cart.remove', 1));
        $this->assertArrayNotHasKey(1, session('cart'));

        // Scene 2 (remove all items)
        session(['cart' => $cart]);
        $response = $this->get(route('cart.remove', 2));
        $this->assertArrayNotHasKey(2, session('cart'));
        $response = $this->get(route('cart.remove', 1));
        $this->assertEmpty(session('cart'));
    }

    public function test_final_order_clears_cart_and_generates_order_id()
    {
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 50, 'quantity' => 1],
        ];

        session(['cart' => $cart]);

        // Scene 1
        $response = $this->post(route('cart.checkout'));

        $this->assertEmpty(session('cart'));
        $response->assertStatus(200);
        $response->assertViewHas('orderId');
        $orderId = $response->viewData('orderId');
        $this->assertMatchesRegularExpression('/^BSS-\d{5}$/', $orderId);

        // Scene 2 (empty cart)
        session(['cart' => []]);

        $response = $this->post(route('cart.checkout'));

        $this->assertEmpty(session('cart'));
        $response->assertStatus(200);
        $response->assertViewHas('orderId');
        $orderId = $response->viewData('orderId');
        $this->assertMatchesRegularExpression('/^BSS-\d{5}$/', $orderId);
    }

    public function test_show_cart_displays_correct_discount_for_pix()
    {
        // Scene 1
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 200, 'quantity' => 1],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'pix']);

        $total = 200;
        $discountPercentage = 10;
        $discountedTotal = $total * (1 - $discountPercentage / 100);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedFinalValue', number_format($discountedTotal, 2, ',', '.'));

        // Scene 2 (additional product)
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 200, 'quantity' => 1],
            2 => ['name' => 'Produto 2', 'price' => 100, 'quantity' => 2],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'pix']);

        $total = (200 + (100 * 2));
        $discountedTotal = $total * (1 - $discountPercentage / 100);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedFinalValue', number_format($discountedTotal, 2, ',', '.'));
    }

    public function test_show_cart_displays_correct_discount_for_credit_at_sight()
    {
        // Scene 1
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 150, 'quantity' => 2],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'credito_vista']);

        $total = 150 * 2;
        $discountPercentage = 10;
        $discountedTotal = $total * (1 - $discountPercentage / 100);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedFinalValue', number_format($discountedTotal, 2, ',', '.'));

        // Scene 2 (cart with 3 items)
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 150, 'quantity' => 1],
            2 => ['name' => 'Produto 2', 'price' => 100, 'quantity' => 2],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'credito_vista']);

        $total = (150 + (100 * 2));
        $discountedTotal = $total * (1 - $discountPercentage / 100);

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('formattedFinalValue', number_format($discountedTotal, 2, ',', '.'));
    }

    public function test_show_cart_displays_correct_installments_with_interest()
    {
        // Scene 1
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 500, 'quantity' => 1],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'credito_parcelado']);

        $total = 500;
        $months = 7;
        $monthlyInterestRate = 0.01;
        $finalValue = round($total * pow((1 + $monthlyInterestRate), $months), 2);
        $installmentValue = $finalValue / $months;

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('installmentOptions');
        $installmentOptions = $response->viewData('installmentOptions');
        $this->assertArrayHasKey($months, $installmentOptions);
        $this->assertEquals(number_format($installmentValue, 2, ',', '.'), $installmentOptions[$months]['installmentValue']);
        $this->assertEquals(number_format($finalValue, 2, ',', '.'), $installmentOptions[$months]['totalWithInterest']);

        // Scene 2 (cart with 2 products and 10x)
        $cart = [
            1 => ['name' => 'Produto 1', 'price' => 500, 'quantity' => 1],
            2 => ['name' => 'Produto 2', 'price' => 300, 'quantity' => 1],
        ];

        session(['cart' => $cart, 'paymentMethod' => 'credito_parcelado']);

        $total = 500 + 300;
        $months = 10;
        $finalValue = round($total * pow((1 + $monthlyInterestRate), $months), 2);
        $installmentValue = $finalValue / $months;

        $response = $this->get(route('cart.show'));

        $response->assertStatus(200);
        $response->assertViewHas('installmentOptions');
        $installmentOptions = $response->viewData('installmentOptions');
        $this->assertArrayHasKey($months, $installmentOptions);
        $this->assertEquals(number_format($installmentValue, 2, ',', '.'), $installmentOptions[$months]['installmentValue']);
        $this->assertEquals(number_format($finalValue, 2, ',', '.'), $installmentOptions[$months]['totalWithInterest']);
    }
}
