<?php

namespace Bitmap\GraphQL;

class Query
{
    /**
     * @var Query
     */
    protected $parent;
    protected $fields;
    protected $queries;

    public function __construct(array $fields = [], $parent = null)
    {
        $this->parent = $parent;
        $this->fields = $fields;
        $this->queries = [];
    }

    /**
     * @return bool
     */
    protected function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * @return Query
     */
    protected function getRoot()
    {
        return $this->isRoot() ? $this : $this->parent->getRoot();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getField($name)
    {
        return $this->fields[$name];
    }

    public function addField($field)
    {
        $this->fields[] = $field;
    }

    /**
     * @param string $name
     *
     * @return Query|null
     */
    public function getQuery($name)
    {
        return $this->queries[$name];
    }

    /**
     * @param resource $in
     *
     * @return string
     */
    public static function from($in)
    {
        $query = null;
        $fields = [];
        $tabs = 0;
        $buffer = '';
        $res = '';

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
        return $res;
    }

    public function __toString()
    {
        return '';
    }
}