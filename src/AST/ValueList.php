<?php

namespace Krak\AQL\AST;

class ValueList implements Node
{
    public $value;
    public $right;

    public function __construct(Value $value, ValueList $right = null) {
        $this->value = $value;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        return $visitor->visitValueList($this);
    }
}
