<?php

namespace App\Exception;

use Throwable;

class InputValidateException extends \RuntimeException
{
    public function __construct(string $message = "Input is invalid", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}