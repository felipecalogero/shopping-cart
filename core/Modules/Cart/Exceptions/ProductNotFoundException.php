<?php

namespace core\Modules\Cart\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message = 'Produto não encontrado.';
}
