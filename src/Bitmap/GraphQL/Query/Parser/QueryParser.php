<?php

namespace Bitmap\GraphQL\Query\Parser;

use ReflectionClass;
use Bitmap\GraphQL\Query\Parser\States\ParserState;

class QueryParser
{
    protected $query;
    protected $state;
    protected $states;

    public function __construct()
    {
        $this->states = [];

        foreach (scandir(__DIR__ . '/States') as $file) {
            if (strrpos($file, '.php') === 0) {
                $reflection = new ReflectionClass(__NAMESPACE__ . '\\' . preg_replace("/.php^/", '', basename($file)));
                if ($reflection->implementsInterface(ParserState::class)) {
                    $this->addState($reflection->newInstance());
                }
            }
        }
    }

    protected function addState(ParserState $state)
    {
        $this->states[$state->getName()] = $state;

        if ($state->isDefault()) {
            $this->state = $state;
        }
    }

    public function getQuery()
    {

    }

    public function parseStream($input)
    {
        while (false !== $c = fgetc($in)) {
            switch ($c) {
                case " ":
                case "\t":
                case "\n":
                    break;

                case '{':
                    $res .= (str_repeat('\\t', $tabs++) . $buffer);
                    $buffer = '';
                    break;
                case '}':
                    $tabs--;
                    $res .= (str_repeat('\\t', $tabs++) . $buffer);
                    $buffer = '';
                    $fields = [];
                    break;
                case ',':
                    $res .= (str_repeat('\\t', $tabs++) . $buffer);
                    $fields[] = $buffer;
                    $buffer = '';
                    break;
                default:
                    $buffer .= $c;
            }
        }

        fclose($in);
    }

    public function parse($text)
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $text);
        rewind($stream);
        return $this->parseResource($stream);
    }
}