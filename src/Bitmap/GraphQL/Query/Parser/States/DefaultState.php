<?php

namespace Bitmap\GraphQL\Query\Parser\States;

use Bitmap\GraphQL\Query\Parser\QueryParser;

class DefaultState extends ParserState
{
    const NAME = 'default';

    public function getName()
    {
        return self::NAME;
    }

    public function isDefault()
    {
        return true;
    }

    public function onOpeningCurlyBracket(QueryParser $parser)
    {
        $parser->setState(QueryState::NAME);
    }

    public function onClosingCurlyBracket(QueryParser $parser)
    {
        // TODO: Implement onClosingCurlyBracket() method.
    }

    public function onComma(QueryParser $parser)
    {
        // TODO: Implement onComma() method.
    }

    public function onComplete(QueryParser $parser)
    {
        // TODO: Implement onComplete() method.
    }
}