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
        $visitor->visitValueList($this);
        $this->value->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }
}
