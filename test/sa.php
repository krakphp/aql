<?php

use Krak\AQL\{SA, Parser};

describe('EnforceFunc', function() {
    it('Enforces only an allowed set of functions', function() {
        $enforce_func = new SA\EnforceFunc(['a', 'c']);
        try {
            $ast = $this->parser->parse("a() = b()");
            $ast->accept($enforce_func);
        } catch (SA\SAException $e) {
            assert($e->getMessage() == "Function 'c' is not an allowed function.");
        }
    });
});
