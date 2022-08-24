<?php

namespace KaracaCase\Exceptions;

class MethodDoesNotExistsException extends \Exception
{

    protected $message = 'Given method does not exists';
    protected $code = 20404;

    public function __construct(string $method, ?\Throwable $previous = null)
    {
        parent::__construct($this->message . ': ' . $method, $this->code, $previous);
    }

}