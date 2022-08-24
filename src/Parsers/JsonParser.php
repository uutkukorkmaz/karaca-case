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
        header('Content-Type: application/json');
        $args = JSON_PRESERVE_ZERO_FRACTION | $args;
        switch (gettype($this->data)) {
            case 'array':
                return json_encode($this->data, $args);
            case 'object':
                if ($this->data instanceof Collection) {
                    return $this->data->toJson($args);
                }

                return json_encode($this->data, $args);
            default:
                return $this->data;
        }
    }


}