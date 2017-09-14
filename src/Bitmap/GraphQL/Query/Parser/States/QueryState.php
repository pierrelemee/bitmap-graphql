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
        $query = new Query();
        $this->query->setName(trim($this->buffer));
        $this->query->addQuery($query);
        $this->query = $query;
    }

    public function onClosingCurlyBracket(QueryParser $parser)
    {
        if ($this->query->isRoot()) {
            $parser->setState(DefaultState::NAME);
        }

        $this->query = $this->query->getParent();
    }

    public function onComma(QueryParser $parser)
    {
        $this->query->addField($this->buffer);
        $this->clearbuffer();
    }

    public function onStart(QueryParser $parser)
    {
        /** @var Query $query */
        $this->query = new Query();
        $this->clearbuffer();
    }

    public function onComplete(QueryParser $parser)
    {
        $parser->setQuery($this->query);
    }

    public function onCharacter(QueryParser $parser, $character)
    {
        if (strlen(trim($character)) > 0) {
            parent::onCharacter($parser, $character);
        }
    }


}