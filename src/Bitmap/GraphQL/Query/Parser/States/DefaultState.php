<?php

namespace Bitmap\GraphQL\Query\Parser\States;

use Bitmap\GraphQL\Query\Parser\QueryParser;

class DefaultState extends ParserState
{
    public function getName()
    {
        return 'default';
    }

    public function isDefault()
    {
        return true;
    }

    public function onOpeningCurlyBracket(QueryParser $parser)
    {
        // TODO: Implement onOpeningCurlyBracket() method.
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