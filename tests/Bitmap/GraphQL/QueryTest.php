<?php

namespace Tests\Bitmap\GraphQL;

use Bitmap\GraphQL\Query\Parser\QueryParser;
use Bitmap\GraphQL\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    const RESOURCE_QUERY_DIR = __DIR__ . "/resources/queries/";
    protected $parser;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->parser = new QueryParser();
    }

    public function testQuery()
    {
        $query = $this->parser->parse($this->getResourceQuery("get_artist"));

        $this->assertEquals(Query::class, get_class($query));
        $this->assertEquals("artist", $query->getName());
    }

    protected function getResourceQuery($name) {
        if (is_file($resource =  self::RESOURCE_QUERY_DIR . "$name.graphql")) {
            return file_get_contents($resource);
        }

        throw new \Exception("No such resource $name");
    }
}