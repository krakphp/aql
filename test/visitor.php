<?php

use Krak\AQL;

beforeEach(function() {
    $this->parser = new AQL\Parser\ExpressionParser();
    $this->compiler = new AQL\Compiler\ExpressionCompiler();
});

describe('DoubleToSingleQuotesVisitor', function() {
    it('transforms double quoted string to single quoted strings', function() {
        $ast = $this->parser->parse('"s"');
        $ast->accept(new AQL\Visitor\DoubleToSingleQuotesVisitor());
        $s = $this->compiler->compile($ast);
        assert($s == "'s'");
    });
});
