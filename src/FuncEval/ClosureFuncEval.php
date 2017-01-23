<?php

namespace Krak\AQL\FuncEval;

use Krak\AQL\AST;

class ClosureFuncEval implements FuncEval {
    private $predicate;

    public function __construct(\Closure $predicate) {
        $this->predicate = $predicate;
    }

    public function evaluateFunc(AST\Func $func, AST\ASTFactory $factory) {
        $f = $this->predicate;
        return $f($func, $factory);
    }
}
