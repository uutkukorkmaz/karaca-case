<?php

namespace KaracaCase\Exceptions;

class ClassNotFoundException extends \Exception
{
    protected $message = 'Class not found';
    protected $code = 30404;

    public function __construct(string $path, ?\Throwable $previous = null)
    {
        parent::__construct($this->message . ': ' . $path, $this->code, $previous);
    }
}