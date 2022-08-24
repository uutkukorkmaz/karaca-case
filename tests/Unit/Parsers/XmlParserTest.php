<?php

namespace Parsers;

use Illuminate\Support\Str;
use KaracaCase\Models\Product;
use KaracaCase\Parsers\XmlParser;

class XmlParserTest extends \PHPUnit\Framework\TestCase
{

    protected $dummyData = [
        [
            'id' => 1,
            'name' => 'Bar',
            'price' => 100,
            'category' => 'Foo',
        ],
        [
            'id' => 2,
            'name' => 'Baz',
            'price' => 200,
            'category' => 'FooBar',
        ],
    ];

    protected string $dummyPath = __DIR__.'/../../../data/dummy.xml';

    protected string $dummyWithoutModelPath = __DIR__.'/../../../data/dummyWithoutModel.xml';

    /**
     * @test
     */
    public function it_can_encode_data_to_xml_when_using_model()
    {
        $parser = Str::replace("\n", "", XmlParser::fromArray($this->dummyData)->using(Product::class)->encode());
        $this->assertEqualsIgnoringCase(file_get_contents($this->dummyPath), $parser);
    }

    /**
     * @test
     */
    public function it_can_encode_data_to_xml_when_not_using_model()
    {
        $parser = Str::replace("\n", "", XmlParser::fromArray($this->dummyData)->encode());
        $this->assertEqualsIgnoringCase(file_get_contents($this->dummyWithoutModelPath), $parser);
    }

    /**
     * @test
     */
    public function it_can_prepare_data_as_SimpleXMLElement()
    {
        $parser = XmlParser::fromFile($this->dummyPath)->prepare();
        $this->assertInstanceOf(\SimpleXMLElement::class, $parser->data);
    }

}