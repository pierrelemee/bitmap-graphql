<?php

namespace Bitmap\GraphQL\Query\Parser\States;

class DefaultState implements ParserState
{
    public function getName()
    {
        return 'default';
    }

    public function isDefault()
    {
        return true;
    }

    public function onOpeningCurlyBracket()
    {
        // TODO: Implement onOpeningCurlyBracket() method.
    }

    public function onClosingCurlyBracket()
    {
        // TODO: Implement onClosingCurlyBracket() method.
    }

    public function onComma()
    {
        // TODO: Implement onComma() method.
    }

}