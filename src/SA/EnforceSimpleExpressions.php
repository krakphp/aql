<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

/** enforces expressions to be like id op value */
class EnforceSimpleExpressions implements Enforcer
{
    private $ignore;

    public function visitAndExpression(AST\AndExpression $node) {}
    public function visitElement(AST\Element $node) {}
    public function visitExpression(AST\Expression $node) {}
    public function visitIdExpression(AST\IdExpression $node) {}
    public function visitOpExpression(AST\OpExpression $node) {
        if ($this->ignore === $node) {
            return;
        }

        // allow nested expressions
        if ($node->left->expr && !$node->right) {
            return;
        }

        if (!$node->right) {
            throw new SAException('Expressions must have two sides');
        }

        if ($node->right->right) {
            throw new SAException('Cannot chain operator expressions');
        }

        $is_valid = ($node->left->id && $node->right->left->isValue()) ||
            ($node->right->left->id && $node->left->isValue());


        $this->ignore = $node->right;
        if ($is_valid) {
            return;
        }

        throw new SAException('Expression must be between an identifier and value');
    }
}
