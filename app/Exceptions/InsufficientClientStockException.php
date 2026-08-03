<?php

namespace App\Exceptions;

use Exception;

class InsufficientClientStockException extends Exception
{
    protected $message = 'The requested sale quantity exceeds your available assigned inventory stock.';
}
