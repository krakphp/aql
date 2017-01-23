<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Krak\AQL;

class IdFuncEval implements AQL\FuncEval\FuncEval {
    public function evaluateFunc(AQL\AST\Func $func, AQL\AST\ASTFactory $factory) {
        return $func->params->element->value;
    }
}

$parser = new AQL\Parser\ExpressionParser();
$compiler = new AQL\Compiler\ExpressionCompiler();

$ast = $parser->parse('1 = id(1)');
$ast->accept(new AQL\Visitor\FuncEvalVisitor([
    'id' => new IdFuncEval(),
]));
$s = $compiler->compile($ast);
assert($s === '1 = 1');
