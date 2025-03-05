<?php

namespace core\Modules\Cart\Repositories;

interface CartRepositoryInterface
{
    public function getCart(): array;
    public function saveCart(array $cart): void;
    public function clearCart(): void;
}
