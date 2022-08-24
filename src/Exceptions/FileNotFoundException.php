<?php

namespace KaracaCase\Exceptions;


class FileNotFoundException extends \Exception
{
    protected $message = 'File not found';
    protected $code = 10404;

    public function __construct(string $path, ?\Throwable $previous = null)
    {
        parent::__construct($this->message . ': ' . $path, $this->code, $previous);
    }

}