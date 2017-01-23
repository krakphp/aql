<?php

describe('Krak AQL', function() {
    beforeEach(function() {
        $this->parser = new Krak\AQL\Parser\ExpressionParser();
        $this->compiler = new Krak\AQL\Compiler\ExpressionCompiler();
    });
    describe('AST', function() {
        require_once __DIR__ . '/ast.php';
    });
    describe('Parser', function() {
        require __DIR__ . '/parser.php';
    });
    describe('Visitor', function() {
        require __DIR__ . '/visitor.php';
    });
    describe('SA', function() {
        require __DIR__ . '/sa.php';
    });
});
