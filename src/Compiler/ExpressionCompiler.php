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
        if ($expr->right) {
            return $s . ' ' . strtoupper($expr->operator) . ' ' . $this->compileOpExpression($expr->right);
        } else if ($expr->value_list) {
            return $s . ' ' . strtoupper($expr->operator) . ' (' . $this->compileValueList($expr->value_list) . ')';
        }
        return $s;
    }

    private function compileElement(AST\Element $el) {
        if ($el->isValue()) {
            return $this->compileValue($el->value);
        }
        if ($el->isId()) {
            return $this->compileIdExpression($el->id);
        }
        if ($el->isFunc()) {
            return $this->compileFunc($el->func);
        }
        if ($el->isExpr()) {
            return '(' . $this->compileExpression($el->expr) . ')';
        }

    }

    private function compileElementList(AST\ElementList $list) {
        $s = $this->compileElement($list->element);
        if ($list->right) {
            return $s . ', ' . $this->compileElementList($list->right);
        }
        return $s;
    }

    private function compileValueList(AST\ValueList $list) {
        $s = $this->compileValue($list->value);
        if ($list->right) {
            return $s . ', ' . $this->compileValueList($list->right);
        }
        return $s;
    }

    private function compileValue(AST\Value $value) {
        if ($value->string) {
            return $value->string->match;
        }
        if ($value->number) {
            return $value->number->match;
        }
    }

    private function compileIdExpression(AST\IdExpression $expr) {
        $s = $expr->id->match;
        if ($expr->right) {
            return $s . '.' . $this->compileIdExpression($expr->right);
        }
        return $s;
    }

    private function compileFunc(AST\Func $func) {
        $s = $func->params ? $this->compileElementList($func->params) : '';
        return $func->name->match . '(' . $s . ')';
    }
}
