<?php

namespace Krak\AQL\AST;

class ElementList implements Node
{
    public $element;
    public $right;

    public function __construct(Element $element, ElementList $right = null) {
        $this->element = $element;
        $this->right = $right;
    }

    public function accept(Visitor $visitor) {
        $visitor->visitElementList($this);
        $this->element->accept($visitor);
        if ($this->right) {
            $this->right->accept($visitor);
        }
    }

    public function toArray() {
        $els = [$this->element];
        $el = $this->right;
        while ($el) {
            $els[] = $el->element;
            $el = $el->right;
        }
        return $els;
    }
}
