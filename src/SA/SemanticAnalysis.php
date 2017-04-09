<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

class SemanticAnalysis
{
    private $visitor;

    public function __construct(array $enforcers) {
        $this->visitor = new AST\ChainVisitor($enforcers);
    }

    /** @throws SAException */
    public function analyze(AST\Node $expr) {
        $expr->accept($this->visitor);
    }
}
