<?php

namespace Krak\AQL\AST;

interface Visitor
{
    public function visitAndExpression(AndExpression $node);
    public function visitElement(Element $node);
    public function visitExpression(Expression $node);
    public function visitIdExpression(IdExpression $node);
    public function visitOpExpression(OpExpression $node);
}
