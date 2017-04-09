<?php

namespace Krak\AQL\Visitor;

use Krak\AQL\AST;

/** Transforms strings with double quotes to single quotes. This is useful for
    SQL where usually single quotes are used */
class DoubleToSingleQuotesVisitor extends AST\AbstractVisitor
{
    public function visitValue(AST\Value $node) {
        if ($node->string) {
            $node->string->match = "'" . trim($node->string->match, '"') . "'";
        }
    }
}
