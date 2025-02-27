<?php

namespace App\Http\Controllers;

use core\Modules\Cart\Handlers\AddProductHandler;
use core\Modules\Cart\Handlers\ShowCartHandler;
use core\Modules\Cart\Handlers\RemoveProductHandler;
use core\Modules\Cart\Handlers\FinalizeOrderHandler;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $addProductHandler;
    protected $showCartHandler;
    protected $removeProductHandler;
    protected $finalizeOrderHandler;

    public function __construct(
        AddProductHandler $addProductHandler,
        ShowCartHandler $showCartHandler,
        RemoveProductHandler $removeProductHandler,
        FinalizeOrderHandler $finalizeOrderHandler
    ) {
        $this->addProductHandler = $addProductHandler;
        $this->showCartHandler = $showCartHandler;
        $this->removeProductHandler = $removeProductHandler;
        $this->finalizeOrderHandler = $finalizeOrderHandler;
    }

    public function addToCart(Product $product)
    {
        $response = $this->addProductHandler->handle($product);
        return response()->json($response);
    }

    public function showCart()
    {
        $response = $this->showCartHandler->handle();
        return view('cart.index', $response);
    }

    public function removeFromCart(Product $product)
    {
        $response = $this->removeProductHandler->handle($product->id);
        return redirect()->route('cart.show')->with('success', 'Product removed from cart successfully!');
    }

    public function finalizeOrder(Request $request)
    {
        $response = $this->finalizeOrderHandler->handle($request);
        return view('cart.checkout', $response);
    }
}
