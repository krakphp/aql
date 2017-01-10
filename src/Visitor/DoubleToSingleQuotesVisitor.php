<?php

namespace Krak\AQL\Visitor;

use Krak\AQL\AST;

/** Transforms strings with double quotes to single quotes. This is useful for
    SQL where usually single quotes are used */
class DoubleToSingleQuotesVisitor implements AST\Visitor
{
    public function visitAndExpression(AST\AndExpression $node) {}
    public function visitElement(AST\Element $node) {}
    public function visitExpression(AST\Expression $node) {}
    public function visitIdExpression(AST\IdExpression $node) {}
    public function visitOpExpression(AST\OpExpression $node) {}
    public function visitValue(AST\Value $node) {
        if ($node->string) {
            $node->string->match = "'" . trim($node->string->match, '"') . "'";
        }
    }
    public function visitValueList(AST\ValueList $node) {}
}
