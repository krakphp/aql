<?php

namespace Krak\AQL\AST;

class SortExpression implements Node
{
    public $id;
    public $ascending;

    public function __construct(IdExpression $id, $ascending = true) {
        $this->id = $id;
        $this->ascending = $ascending;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitSortExpression($this);
        $this->id->accept($visitor);
    }
}
