<?php

namespace KaracaCase\Parsers;

use KaracaCase\Models\Product;
use Illuminate\Support\Collection;
use KaracaCase\Parsers\ParserAbstract as Parser;

class JsonParser extends Parser
{

    public function prepare()
    {
        $this->data = json_decode($this->data, true);

        return $this;
    }


    public function encode($args = 0)
    {
        $args = JSON_PRESERVE_ZERO_FRACTION | $args;

        return match (gettype($this->data)) {
            'array' => json_encode($this->data, $args),
            default => $this->data,
        };
    }


}