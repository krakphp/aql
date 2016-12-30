<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

/** checks that each identifier  */
class EnforceDomain implements Enforcer
{
    private $domain_tree;
    private $last_node;
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

    public function visitAndExpression(AST\AndExpression $node) {
        $this->last_node = $node;
    }
    public function visitElement(AST\Element $node) {
        $this->last_node = $node;
    }
    public function visitExpression(AST\Expression $node) {
        $this->last_node = $node;
    }
    public function visitIdExpression(AST\IdExpression $node) {
        if (!$this->last_node instanceof AST\IdExpression) {
            $this->cur_tree = $this->domain_tree;
        }
        $this->last_node = $node;

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
    public function visitOpExpression(AST\OpExpression $node) {
        $this->last_node = $node;
    }
    public function visitValue(AST\Value $node) {
        $this->last_node = $node;
    }
    public function visitValueList(AST\ValueList $node) {
        $this->last_node = $node;
    }
}
