<?php

use KaracaCase\Models\Product;
use KaracaCase\Parsers\JsonParser;

header('Content-Type: application/xml');

return JsonParser::fromFile('data/products.json')
    ->prepare()
    ->using(Product::class)
    ->convertWith(\KaracaCase\Parsers\XmlParser::class);