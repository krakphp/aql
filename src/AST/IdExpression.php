<?php

namespace Krak\AQL\AST;

class IdExpression implements Node
{
    public $id;
    public $right;

    public function __construct($id, IdExpression $right = null) {
        $this->id = $id;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitIdExpression($this);

        if ($this->right) {
            $this->right->accept($visitor);
        }
    }
}
