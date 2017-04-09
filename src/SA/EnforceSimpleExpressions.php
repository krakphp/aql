<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

/** enforces expressions to be like id op value */
class EnforceSimpleExpressions extends AbstractEnforcer
{
    private $ignore;

    public function visitOpExpression(AST\OpExpression $node) {
        if ($this->ignore === $node) {
            return;
        }

        // allow nested expressions
        if ($node->left->expr && !$node->right) {
            return;
        }

        if ($node->right) {
            if ($node->right->right) {
                throw new SAException('Cannot chain operator expressions');
            }

            $is_valid = $this->nodeIsAllowed($node);

            $this->ignore = $node->right;
            if ($is_valid) {
                return;
            }
        } else if ($node->value_list && $node->left->id) {
            return;
        } else {
            throw new SAException('Expressions must have two sides');
        }

        throw new SAException('Expression must be between an identifier and value');
    }

    private function nodeIsAllowed($node) {
        return (
            $node->left->isId() && ($node->right->left->isValue() || $node->right->left->isFunc())
        ) || (
            $node->right->left->isId() && ($node->left->isValue() || $node->left->isFunc())
        );
    }
}
