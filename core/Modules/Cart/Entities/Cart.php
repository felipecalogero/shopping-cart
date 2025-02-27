<?php

namespace core\Modules\Cart\Entities;

class Cart
{
    public static function addProduct($cart, $product, $quantity)
    {
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity
            ];
        }

        return $cart;
    }

    public static function removeProduct($cart, $productId)
    {
        unset($cart[$productId]);
        return $cart;
    }
}
