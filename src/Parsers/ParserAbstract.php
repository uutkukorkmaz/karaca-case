<?php

namespace KaracaCase\Parsers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

abstract class ParserAbstract extends BaseParser
{

    abstract public function prepare();

    abstract public function encode($args);

    public function using(string $class)
    {
        $group = explode('\\', $class);
        $group = Str::plural(Str::lower(end($group)));
        $temp[$group] = new Collection();
        foreach ($this->data as $entry) {
            $temp[$group]->add(new $class($entry));
        }
        $this->data = $temp;

        return $this;
    }

}