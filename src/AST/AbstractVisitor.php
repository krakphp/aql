<?php

namespace Krak\AQL\AST;

abstract class AbstractVisitor implements Visitor
{
    public function visitAndExpression(AndExpression $node) {}
    public function visitElement(Element $node) {}
    public function visitElementList(ElementList $node) {}
    public function visitExpression(Expression $node) {}
    public function visitIdExpression(IdExpression $node) {}
    public function visitOpExpression(OpExpression $node) {}
    public function visitValue(Value $node) {}
    public function visitValueList(ValueList $node) {}
    public function visitFunc(Func $node) {}
    public function visitSortExpressionList(SortExpressionList $node) {}
    public function visitSortExpression(SortExpression $node) {}
}
