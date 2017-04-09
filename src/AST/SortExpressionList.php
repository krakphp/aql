<?php

namespace Krak\AQL\AST;

class SortExpressionList implements Node
{
    public $sort_expression;
    public $right;

    public function __construct(SortExpression $sort_expression, SortExpressionList $right = null) {
        $this->sort_expression = $sort_expression;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitSortExpressionList($this);
        $this->sort_expression->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }

    public function toArray() {
        $els = [$this->sort_expression];
        $el = $this->right;
        while ($el) {
            $els[] = $el->sort_expression;
            $el = $el->right;
        }
        return $els;
    }
}
