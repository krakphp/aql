<?php

namespace Krak\AQL\AST;

class AndExpression implements Node
{
    public $left;
    public $right;

    public function __construct(OpExpression $left, AndExpression $right = null) {
        $this->left = $left;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitAndExpression($this);
        $this->left->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }
}
