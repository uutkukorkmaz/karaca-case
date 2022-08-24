<?php

namespace KaracaCase\Parsers;

use Illuminate\Support\Str;
use KaracaCase\Models\BaseModel;
use Illuminate\Support\Collection;
use KaracaCase\Parsers\ParserAbstract as Parser;

class XmlParser extends Parser
{

    public function prepare()
    {
        $this->data = new \SimpleXMLElement($this->data);

        return $this;
    }

    public function encode($args = [])
    {
        $xml = $this->createXML($this->data);
        return $xml->asXML();
    }

    private function createXML($data, $xml = null)
    {
        if (is_null($xml)) {
            $xml = new \SimpleXMLElement('<?xml version="1.0"?><dataset/>');
            $xml->addAttribute('xmlns', 'http://www.w3.org/2001/XMLSchema');
        }
        foreach ($data as $key => $value) {
            switch (true) {
                case is_array($value):
                    if (is_numeric($key)) {
                        $key = 'item'.($key + 1);
                    }
                    $child = $xml->addChild($key);
                    $this->createXML($value, $child);
                    break;
                case $value instanceof Collection:
                    $child = $xml->addChild($key);
                    $value->each(function ($item, $attribute) use (&$child, $key) {
                        $this->createXML($item, $child->addChild(Str::singular($key)));
                    });
                    break;
                default:
                    $xml->addChild($key, gettype($value) == 'double' ? number_format($value, 2) : $value);
            }
        }

        return $xml;
    }


}