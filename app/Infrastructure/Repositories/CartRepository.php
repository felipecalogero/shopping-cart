<?php

namespace App\Infrastructure\Repositories;

use core\Modules\Cart\Repositories\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function saveCart(array $cart): void
    {
        $_SESSION['cart'] = $cart;
    }

    public function clearCart(): void
    {
        unset($_SESSION['cart']);
    }
}
