<?php

namespace Krak\AQL\AST;

class Func implements Node
{
    public $name;
    public $params;

    public function __construct($name, ElementList $params = null) {
        $this->name = $name;
        $this->params = $params;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitFunc($this);
        if ($this->params) {
            $this->params->accept($params);
        }
    }
}
