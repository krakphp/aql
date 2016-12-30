<?php

namespace Krak\AQL\AST;

class Element implements Node
{
    public $value;
    public $id;
    public $expr;

    public function isValue() {
        return $this->value;
    }

    public static function value(Value $value) {
        $el = new self();
        $el->value = $value;
        return $el;
    }
    public static function id(IdExpression $id) {
        $el = new self();
        $el->id = $id;
        return $el;
    }
    public static function expr(Expression $expr) {
        $el = new self();
        $el->expr = $expr;
        return $el;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitElement($this);

        if ($this->value) {
            $this->value->accept($visitor);
        }
        if ($this->id) {
            $this->id->accept($visitor);
        } else if ($this->expr) {
            $this->expr->accept($visitor);
        }
    }
}
