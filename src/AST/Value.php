<?php

namespace Krak\AQL\AST;

class Value implements Node
{
    public $string;
    public $number;

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

    public function accept(Visitor $visitor) {
        return $visitor->visitValue($this);
    }
}
