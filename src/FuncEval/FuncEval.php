<?php

namespace Krak\AQL\FuncEval;

use Krak\AQL\AST;

interface FuncEval {
    /** @return mixed */
    public function evaluateFunc(AST\Func $func, AST\ASTFactory $factory);
}
