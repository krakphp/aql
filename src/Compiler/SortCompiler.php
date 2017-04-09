<?php

namespace Krak\AQL\Compiler;

use Krak\AQL\AST;

/** Compiles an expression back into a string */
class SortCompiler implements Compiler
{
    use CompileIdExpression;

    public function compile(AST\Node $expr) {
        return $this->compileSortExpressionList($expr);
    }

    private function compileSortExpressionList(AST\SortExpressionList $expr) {
        $s = $this->compileSortExpression($expr->sort_expression);
        if ($expr->right) {
            $s .= ', ' . $this->compileSortExpressionList($expr->right);
        }
        return $s;
    }

    private function compileSortExpression(AST\SortExpression $expr) {
        $id = $this->compileIdExpression($expr->id);
        return $id . ' ' . ($expr->ascending ? 'ASC' : 'DESC');
    }
}
