<?php

namespace Bitmap\GraphQL;

use Bitmap\Query\Select;

class BitmapQL
{
    protected $registry;

    public function __construct() {
        $this->registry = [];
    }

    public function register($name, $class) {
        $this->registry[$name] = $class;
    }

    public function execute(Query $query) {
        echo $query->getName() . PHP_EOL;
        if (isset($this->registry[$query->getName()])) {
            //$reflection = new \ReflectionClass();
            /* @var Select $select */
            $select = call_user_func([$this->registry[$query->getName()], 'select']);
            return $select->one();
        }
    }
}