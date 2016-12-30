<?php

namespace Krak\AQL\AST;

class Expression implements Node
{
    public $left;
    public $right;

    public function __construct(AndExpression $left, Expression $right = null) {
        $this->left = $left;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitExpression($this);

        $this->left->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }
}
