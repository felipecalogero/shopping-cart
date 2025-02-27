<?php

namespace core\Modules\Cart\Exceptions;

use Exception;

class ProductNotInCartException extends Exception
{
    protected $message = 'Produto não encontrado no carrinho.';
}
