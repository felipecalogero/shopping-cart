<?php

namespace core\Modules\Cart\Entities;

class Cart
{
    private $items = [];

    public function addProduct($product, $quantity)
    {
        if (isset($this->items[$product->id])) {
            $this->items[$product->id]['quantity'] += $quantity;
        } else {
            $this->items[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity
            ];
        }
    }

    public function removeProduct($productId)
    {
        unset($this->items[$productId]);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }
}
