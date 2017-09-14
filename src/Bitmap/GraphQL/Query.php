<?php

namespace Bitmap\GraphQL;

use Bitmap\Bitmap;

class Query
{
    protected $name;
    /**
     * @var Query
     */
    protected $parent;
    protected $fields;
    protected $queries;

    public function __construct(array $fields = [], $name = '', $parent = null)
    {
        $this->name   = $name;
        $this->parent = $parent;
        $this->fields = $fields;
        $this->queries = [];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Query
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return boolean
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * @return bool
     */
    public function isRoot()
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
     * @return Query
     */
    public function getParent()
    {
        return $this->parent;
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

    public function addQuery(Query $query)
    {
        $this->queries[$query->getName()] = $query;
    }

    public function execute() {
        $mapper = Bitmap::current()->getMapper($this->name);
        var_dump($mapper);
        $class = $mapper->getClass();
        var_dump($class);
        return $class::select()->one();
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

    public function addQuery($name, Query $query)
    {
        $this->queries[$name] = $query;
        $query->parent = $this;
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