<?php

namespace Krak\AQL\Visitor;

use Krak\AQL\AST;

class FuncEvalVisitor extends AST\AbstractVisitor
{
    private $eval_map;
    private $factory;

    public function __construct(array $eval_map, AST\ASTFactory $factory = null) {
        $this->eval_map = $eval_map;
        $this->factory = $factory ?: new AST\ASTFactory();
    }

    public function visitElement(AST\Element $node) {
        if (!$node->func) {
            return;
        }

        if (!array_key_exists($node->func->name->match, $this->eval_map)) {
            return;
        }

        $eval = $this->eval_map[$node->func->name->match];
        $val = $eval->evaluateFunc($node->func, $this->factory);

        $node->func = null;
        if ($val instanceof AST\Value) {
            $node->value = $val;
        } else if ($val instanceof AST\IdExpression) {
            $node->id = $val;
        } else if ($val instanceof AST\Func) {
            $node->func = $val;
        } else if ($val instanceof AST\Expression) {
            $node->expr = $val;
        }
    }
}
