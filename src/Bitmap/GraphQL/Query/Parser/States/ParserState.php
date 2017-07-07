<?php

namespace Bitmap\GraphQL\Query\Parser\States;

interface ParserState
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isDefault();

    public function onOpeningCurlyBracket();

    public function onClosingCurlyBracket();

    public function onComma();
}