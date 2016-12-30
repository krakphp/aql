<?php

namespace Krak\AQL\AST;

class OpExpression implements Node
{
    public $left;
    public $operator;
    public $right;

    public function __construct(Element $left, $operator = null, OpExpression $right = null) {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitOpExpression($this);

        $this->left->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }
}
