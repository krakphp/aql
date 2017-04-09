<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

/** checks that each identifier  */
class EnforceDomain extends AbstractEnforcer
{
    private $domain_tree;
    private $next_id;
    private $cur_tree;

    /** example domain tree

        ```php
        [
            'user' => [
                'id',
                'email',
                'group' => ['id', 'name']
            ]
        ]
        ```
    */
    public function __construct(array $domain_tree) {
        $this->domain_tree = $domain_tree;
    }

    public function visitIdExpression(AST\IdExpression $node) {
        if ($this->next_id != $node) {
            $this->cur_tree = $this->domain_tree;
        }
        $this->next_id = $node->right;

        $valid = is_array($this->cur_tree) &&
            (
                array_key_exists($node->id->match, $this->cur_tree) ||
                in_array($node->id->match, $this->cur_tree)
            );

        if (!$valid) {
            throw new SAException('identifier is not within the valid domain');
        }


        if (isset($this->cur_tree[$node->id->match])) {
            $this->cur_tree = $this->cur_tree[$node->id->match];
        }
    }
}
