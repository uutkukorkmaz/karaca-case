<?php

use KaracaCase\Models\Product;
use KaracaCase\Parsers\JsonParser;

return JsonParser::fromFile('data/products.json')
    ->prepare()
    ->using(Product::class)
    ->convertWith(\KaracaCase\Parsers\XmlParser::class);