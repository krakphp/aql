<?php

namespace Krak\AQL\Visitor;

use Krak\AQL\AST;

class RenameIdVisitor implements AST\Visitor
{
    private $rename_map;
    private $ignore;

    public function __construct(array $rename_map) {
        $this->rename_map = $rename_map;
    }

    public function visitAndExpression(AST\AndExpression $node) {}
    public function visitElement(AST\Element $node) {}
    public function visitExpression(AST\Expression $node) {}
    public function visitIdExpression(AST\IdExpression $node) {
        if ($this->ignore === $node) {
            $this->ignore = $node->right;
            return;
        }

        if (isset($this->rename_map[$node->id->match])) {
            $node->id->match = $this->rename_map[$node->id->match];
        }
    }
    public function visitOpExpression(AST\OpExpression $node) {}
    public function visitValue(AST\Value $node) {}
    public function visitValueList(AST\ValueList $node) {}
}
