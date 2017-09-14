<?php

namespace Tests\Bitmap\GraphQL;

use Bitmap\GraphQL\Query\Parser\QueryParser;
use Bitmap\GraphQL\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    protected $parser;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->parser = new QueryParser();
    }

    public function testQuery()
    {
        $query = $this->parser->parse("{
            artist {
                name
            }
        }");

        $this->assertEquals(Query::class, get_class($query));
        $this->assertEquals("artist", $query->getName());
    }
}