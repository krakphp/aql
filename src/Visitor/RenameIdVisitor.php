<?php

namespace Krak\AQL\Visitor;

use Krak\AQL\AST;

class RenameIdVisitor implements AST\Visitor
{
    private $rename_map;
    private $previous_ids;
    private $next_id;

    public function __construct(array $rename_map) {
        $this->rename_map = $rename_map;
        $this->previous_ids = [];
    }

    public function visitAndExpression(AST\AndExpression $node) {}
    public function visitElement(AST\Element $node) {}
    public function visitExpression(AST\Expression $node) {}
    public function visitIdExpression(AST\IdExpression $node) {
        if ($this->next_id !== $node) {
            $this->previous_ids = [];
        }

        $this->next_id = $node->right;

        // store a copy before appending the current id
        $ids = $this->previous_ids;
        $this->previous_ids[] = $node->id->match;

        // append the current identifier
        $ids[] = $node->id->match;
        $key = implode('.', $ids);

        if (isset($this->rename_map[$key])) {
            $node->id->match = $this->rename_map[$key];
        }
    }
    public function visitOpExpression(AST\OpExpression $node) {}
    public function visitValue(AST\Value $node) {}
    public function visitValueList(AST\ValueList $node) {}
    public function visitElementList(AST\ElementList $node) {}
    public function visitFunc(AST\Func $node) {}
}
