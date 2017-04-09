<?php

namespace Krak\AQL\SA;

use Krak\AQL\AST;

class EnforceFunc extends AbstractEnforcer
{
    private $allowed_funcs;

    public function __construct(array $allowed_funcs) {
        $this->allowed_funcs = $allowed_funcs;
    }
    public function visitFunc(AST\Func $node) {
        if (in_array($node->name->match, $this->allowed_funcs)) {
            return;
        }

        throw new SAException(sprintf("Function '%s' is not an allowed function.", $node->name->match));
    }
}
