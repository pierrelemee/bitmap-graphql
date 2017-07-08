<?php

namespace Bitmap\GraphQL\Query\Parser\States;

use Bitmap\GraphQL\Query;
use Bitmap\GraphQL\Query\Parser\QueryParser;

class QueryState extends ParserState
{
    const NAME = 'query';

    /**
     * @var Query $query
     */
    protected $query;
    protected $count = 0;

    /**
     * @return string
     */
    public function getName() {
        return self::NAME;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return false;
    }

    public function onOpeningCurlyBracket(QueryParser $parser)
    {
        $this->count++;
    }

    public function onClosingCurlyBracket(QueryParser $parser)
    {
        $this->count--;

        if ($this->count < 0) {
            $parser->setState(DefaultState::NAME);
        }
    }

    public function onComma(QueryParser $parser)
    {
        $this->query->addField($this->buffer);
        $this->clearbuffer();
    }

    public function onStart(QueryParser $parser)
    {
        $this->query = new Query();
    }

    public function onComplete(QueryParser $parser)
    {
        $parser->setQuery($this->query);
    }
}