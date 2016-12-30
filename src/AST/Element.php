<?php

namespace Krak\AQL\AST;

class Element implements Node
{
    public $string;
    public $number;
    public $id;
    public $expr;

    public function isValue() {
        return $this->string || $this->number;
    }

    public static function string($string) {
        $el = new self();
        $el->string = $string;
        return $el;
    }
    public static function number($number) {
        $el = new self();
        $el->number = $number;
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
        if ($this->id) {
            $this->id->accept($visitor);
        } else if ($this->expr) {
            $this->expr->accept($visitor);
        }

        return $visitor->visitElement($this);
    }
}
