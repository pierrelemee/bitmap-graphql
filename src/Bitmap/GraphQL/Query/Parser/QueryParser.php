<?php

namespace Bitmap\GraphQL\Query\Parser;

use Bitmap\GraphQL\Query;
use ReflectionClass;
use Bitmap\GraphQL\Query\Parser\States\ParserState;
use Exception;

class QueryParser
{
    protected $query;
    /**
     * @var ParserState $state
     */
    protected $state;
    protected $states;

    public function __construct()
    {
        $this->states = [];

        foreach (scandir(__DIR__ . '/States') as $file) {
            $extension = substr($file, strrpos($file, '.') + 1);
            if (strtolower($extension) === 'php') {
                $reflection = new ReflectionClass(__NAMESPACE__ . '\\States\\' . preg_replace("/.php^/", '', basename(substr($file, 0, strrpos($file, '.')))));
                if ($reflection->isInstantiable() && $reflection->isSubclassOf(ParserState::class)) {
                    $this->addState($reflection->newInstance());
                }
            }
        }
    }

    protected function addState(ParserState $state)
    {
        $this->states[$state->getName()] = $state;
    }

    public function setState($state)
    {
        if (is_object($state)) {
            $this->state = $state;
        } else {
            if (isset($this->states[$state])) {
                if (null !== $this->state) {
                    $this->state->onComplete($this);
                }
                $this->state = $this->states[$state];
                $this->state->onStart($this);
            } else {
                throw new Exception("Unknown parser state $state");
            }
        }
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery(Query $query)
    {
        $this->query = $query;
    }

    /**
     * @param $input resource
     *
     * @return Query
     *
     * @throws Exception
     */
    public function parseStream($input)
    {
        $this->query = null;
        foreach ($this->states as $state) {
            echo $state->getName() . PHP_EOL;
            if ($state->isDefault()) {
                $this->setState($state);
            }
        }

        while (false !== $character = fgetc($input)) {
            switch ($character) {
                //case " ":
                //case "\t":
                //case "\n":
                //    break;
                case '{':
                    $this->state->onOpeningCurlyBracket($this);
                    break;
                case '}':
                    $this->state->onClosingCurlyBracket($this);
                    break;
                case ',':
                    $this->state->onComma($this);
                    break;
                default:
                    $this->state->onCharacter($this, $character);
            }
        }

        fclose($input);

        if (is_null($this->query)) {
            throw new Exception("No query could be parsed");
        }

        return $this->query;
    }

    /**
     * @param $text string
     *
     * @return Query
     *
     * @throws Exception
     */
    public function parse($text)
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $text);
        rewind($stream);
        return $this->parseStream($stream);
    }
}