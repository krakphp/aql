<?php

use Krak\AQL\AST;

describe('ASTFactory', function() {
    beforeEach(function() {
        $this->factory = new AST\ASTFactory();
    });
    describe('->createStringValue()', function() {
        it('creates a string value', function() {
            $el = $this->factory->createStringValue('s');
            assert($el instanceof AST\Value && $el->string->match == '"s"');
        });
    });
    describe('->createNumberValue()', function() {
        it('creates a number value', function() {
            $el = $this->factory->createNumberValue(1);
            assert($el instanceof AST\Value && $el->number->match == 1);
        });
    });
});
