<?php

use Krak\AQL;

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
describe('FuncEvalVisitor', function() {
    it('evaluates and transforms funcs', function() {
        $ast = $this->parser->parse('3 = add(1,2)');
        $ast->accept(new AQL\Visitor\FuncEvalVisitor([
            'add' => new AQL\FuncEval\ClosureFuncEval(function($func, $factory) {
                $params = $func->params->toArray();
                $params = array_map(function($el) {
                    return (int) $el->value->number->match;
                }, $params);
                $sum = array_sum($params);
                return $factory->createNumberValue($sum);
            })
        ]));
        $s = $this->compiler->compile($ast);
        assert($s == "3 = 3");
    });
});
