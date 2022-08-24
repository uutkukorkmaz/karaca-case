<?php

namespace Tests\Unit\Parsers;

use KaracaCase\Models\Product;
use PHPUnit\Framework\TestCase;
use KaracaCase\Parsers\XmlParser;
use KaracaCase\Parsers\JsonParser;
use Illuminate\Support\Collection;
use KaracaCase\Exceptions\FileNotFoundException;
use KaracaCase\Exceptions\ClassNotFoundException;
use KaracaCase\Exceptions\MethodDoesNotExistsException;

class JsonParserTest extends TestCase
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

    protected string $dummyPath = __DIR__.'/../../../data/dummy.json';

    /**
     * @test
     */
    public function it_can_read_from_file()
    {
        $parser = JsonParser::fromFile($this->dummyPath);
        $this->assertSame(json_encode($this->dummyData), $parser->data);
    }

    /**
     * @test
     */
    public function it_can_read_from_array()
    {
        $parser = JsonParser::fromArray($this->dummyData);
        $this->assertSame($this->dummyData, $parser->data);
    }

    /**
     * @test
     */
    public function it_can_read_from_collection()
    {
        $collection = collect($this->dummyData);
        $parser = JsonParser::fromCollection($collection);
        $this->assertSameSize(collect(json_decode(file_get_contents($this->dummyPath), true)), $parser->data);
    }

    /**
     * @test
     */
    public function it_can_prepare_data_from_json()
    {
        $parser = JsonParser::fromFile($this->dummyPath)->prepare();
        $this->assertSame(json_decode(json_encode($this->dummyData), JSON_PRESERVE_ZERO_FRACTION), $parser->data);
    }

    /**
     * @test
     */
    public function it_can_encode_data_as_json()
    {
        $parser = JsonParser::fromFile($this->dummyPath)->prepare();
        $this->assertSame(json_encode($this->dummyData), $parser->encode());
    }

    /**
     * @test
     */
    public function it_can_encode_data_from_json_as_model()
    {
        $parser = JsonParser::fromFile($this->dummyPath)->prepare()->using(Product::class);

        $this->assertSameSize($this->convertDummyDataToModelEntry(), $parser->data);
    }

    /**
     * @test
     */
    public function it_can_convert_data_from_json_to_xml()
    {
        $parsedXML = JsonParser::fromFile($this->dummyPath)
            ->prepare()
            ->using(Product::class)
            ->convertWith(XmlParser::class);

        $this->assertEquals(XmlParser::fromArray($this->convertDummyDataToModelEntry())->encode(), $parsedXML);
    }

    /**
     * @test
     */
    public function it_must_throw_exception_when_file_not_exists()
    {
        $this->expectException(FileNotFoundException::class);

        JsonParser::fromFile('this-file-does-not-exists.json');
    }

    /**
     * @test
     */
    public function it_must_throw_exception_when_converter_class_not_exists()
    {
        $this->expectException(ClassNotFoundException::class);

        JsonParser::fromFile($this->dummyPath)
            ->prepare()
            ->using(Product::class)
            ->convertWith('ThisClassDoesNotExists');
    }

    /**
     * @test
     */
    public function it_must_throw_exception_when_parser_has_no_such_static_method(){
        $this->expectException(MethodDoesNotExistsException::class);

        JsonParser::thisMethodDoesNotExists();
    }

    private function convertDummyDataToModelEntry()
    {
        $dummyModel['products'] = new Collection();
        foreach ($this->dummyData as $entry) {
            $dummyModel['products']->add(new Product($entry));
        }


        return $dummyModel;
    }

}