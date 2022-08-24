<?php

namespace KaracaCase\Parsers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use KaracaCase\Exceptions\FileNotFoundException;
use KaracaCase\Exceptions\ClassNotFoundException;
use KaracaCase\Exceptions\MethodDoesNotExistsException;

class BaseParser
{

    public function __construct(protected mixed $data = null)
    {
    }

    public static function __callStatic($name, $args)
    {
        $self = new static();
        if (str_contains($name, 'from')) {
            $method = Str::replace('from', '', $name);
            switch (Str::lower($method)) {
                default:
                case 'file':
                    return $self->readFromFile($args[0]);
                case 'collection':
                    return $self->readFromCollection($args[0]);
                case 'array':
                    return $self->readFromArray($args[0]);
                // todo implement other formats
            }
        }

        throw new MethodDoesNotExistsException($name);
    }


    private function readFromFile(string $file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        $this->data = file_get_contents($file);

        return $this;
    }

    private function readFromCollection(Collection $collection)
    {
        $this->data = $collection;

        return $this;
    }

    private function readFromArray(array $array)
    {
        $this->data = $array;

        return $this;
    }


    public function convertWith(string $parser)
    {
        if (!class_exists($parser)) {
            throw new ClassNotFoundException($parser);
        }
        $parser = new $parser($this->data);

        return $parser->encode();
    }

}