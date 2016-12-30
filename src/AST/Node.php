<?php

namespace Krak\AQL\AST;

interface Node {
    public function accept(Visitor $visitor);
}
