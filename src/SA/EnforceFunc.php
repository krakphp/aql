<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

class EnforceFunc implements Enforcer
{
    private $allowed_funcs;

    public function __construct(array $allowed_funcs) {
        $this->allowed_funcs = $allowed_funcs;
    }
    public function visitAndExpression(AST\AndExpression $node) {}
    public function visitElement(AST\Element $node) {}
    public function visitElementList(AST\ElementList $node) {}
    public function visitExpression(AST\Expression $node) {}
    public function visitIdExpression(AST\IdExpression $node) {}
    public function visitOpExpression(AST\OpExpression $node) {}
    public function visitValue(AST\Value $node) {}
    public function visitValueList(AST\ValueList $node) {}
    public function visitFunc(AST\Func $node) {
        if (in_array($node->name->match, $this->allowed_funcs)) {
            return;
        }

        throw new SAException(sprintf("Function '%s' is not an allowed function.", $node->name->match));
    }
}
