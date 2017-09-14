<?php

namespace Bitmap\GraphQL\Query\Parser\States;

use Bitmap\GraphQL\Query\Parser\QueryParser;

abstract class ParserState
{
    protected $buffer;

    public function __construct()
    {
        $this->buffer = '';
    }

    public function clearbuffer()
    {
        $this->buffer = '';
    }

    /**
     * @return string
     */
    public abstract function getName();

    /**
     * @return bool
     */
    public abstract function isDefault();

    public abstract function onOpeningCurlyBracket(QueryParser $parser);

    public abstract function onClosingCurlyBracket(QueryParser $parser);

    public abstract function onComma(QueryParser $parser);

    public function onStart(QueryParser $parser)
    {
        // Override if needed
    }

    public function onComplete(QueryParser $parser)
    {
        // Override if needed
    }

    public function onCharacter(QueryParser $parser, $character)
    {
        $this->buffer .= $character;
    }
}