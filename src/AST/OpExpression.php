<?php

namespace Krak\AQL\AST;

class OpExpression implements Node
{
    public $left;
    public $operator;
    public $right;
    public $value_list;

    public function __construct(Element $left, $operator = null, OpExpression $right = null, ValueList $value_list = null) {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
        $this->value_list = $value_list;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitOpExpression($this);

        $this->left->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
        if ($this->value_list) {
            $this->value_list->accept($visitor);
        }
    }
}
