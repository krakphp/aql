<?php

namespace Krak\AQL\Compiler;

use Krak\AQL\AST;

/** Compiles an expression back into a string */
class ExpressionCompiler implements Compiler
{
    public function compile(AST\Expression $expr) {
        return $this->compileExpression($expr);
    }

    private function compileExpression(AST\Expression $expr) {
        $s = $this->compileAndExpression($expr->left);
        if ($expr->right) {
            return $s . ' OR ' . $this->compileExpression($expr->right);
        }
        return $s;
    }

    private function compileAndExpression(AST\AndExpression $expr) {
        $s = $this->compileOpExpression($expr->left);
        if ($expr->right) {
            return $s . ' AND ' . $this->compileAndExpression($expr->right);
        }
        return $s;
    }

    private function compileOpExpression(AST\OpExpression $expr) {
        $s = $this->compileElement($expr->left);
        if ($expr->operator) {
            return $s . ' ' . $expr->operator . ' ' . $this->compileOpExpression($expr->right);
        }
        return $s;
    }

    private function compileElement(AST\Element $el) {
        if ($el->string) {
            return $el->string->match;
        }
        if ($el->number) {
            return $el->number->match;
        }
        if ($el->id) {
            return $this->compileIdExpression($el->id);
        }
        if ($el->expr) {
            return '(' . $this->compileExpression($el->expr) . ')';
        }
    }

    private function compileIdExpression(AST\IdExpression $expr) {
        $s = $expr->id->match;
        if ($expr->right) {
            return $s . '.' . $this->compileIdExpression($expr->right);
        }
        return $s;
    }
}
