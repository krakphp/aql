<?php

namespace Krak\AQL\Compiler;

use Krak\AQL\AST;

trait CompileIdExpression {
    private function compileIdExpression(AST\IdExpression $expr) {
        $s = $expr->id->match;
        if ($expr->right) {
            return $s . '.' . $this->compileIdExpression($expr->right);
        }
        return $s;
    }
}
