<?php

namespace Krak\AQL\AST;

class ChainVisitor implements Visitor
{
    private $visitors;

    public function __construct(array $visitors) {
        $this->visitors = $visitors;
    }

    public function visitAndExpression(AndExpression $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitAndExpression($node);
        }
    }
    public function visitElement(Element $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitElement($node);
        }
    }
    public function visitElementList(ElementList $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitElementList($node);
        }
    }
    public function visitExpression(Expression $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitExpression($node);
        }
    }
    public function visitIdExpression(IdExpression $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitIdExpression($node);
        }
    }
    public function visitOpExpression(OpExpression $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitOpExpression($node);
        }
    }
    public function visitValue(Value $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitValue($node);
        }
    }
    public function visitValueList(ValueList $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitValueList($node);
        }
    }
    public function visitFunc(Func $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitFunc($node);
        }
    }
    public function visitSortExpressionList(SortExpressionList $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitSortExpressionList($node);
        }
    }
    public function visitSortExpression(SortExpression $node) {
        foreach ($this->visitors as $visitor) {
            $visitor->visitSortExpression($node);
        }
    }
}
