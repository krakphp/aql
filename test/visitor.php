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
describe('RenameIdVisitor', function() {
    it('renames top level domain ids', function() {
        $ast = $this->parser->parse('a = b.a');
        $ast->accept(new AQL\Visitor\RenameIdVisitor([
            'a' => 'alpha',
            'b' => 'beta'
        ]));
        $s = $this->compiler->compile($ast);
        assert($s == "alpha = beta.a");
    });
});
