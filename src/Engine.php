<?php

namespace Krak\AQL;

/** The AQL engine will accept the query input, parse it, perform semantic analysis,
    run any transformers, then compiles it back to a string */
class Engine
{
    private $parser;
    private $sa;
    private $visitor;

    public function __construct(Parser\Parser $parser = null, SA\SemanticAnalysis $sa = null, Compiler\Compiler $compiler = null, AST\Visitor $visitor = null) {
        $this->parser = $parser ?: new Parser\ExpressionParser();
        $this->sa = $sa ?: new SA\SemanticAnalysis([
            new SA\EnforceSimpleExpressions()
        ]);
        $this->compiler = $compiler ?: new Compiler\ExpressionCompiler();
        $this->visitor = $visitor;
    }

    public function process($input) {
        $ast = $this->parser->parse($input);
        $this->sa->analyze($ast);
        if ($this->visitor) {
            $ast->accept($this->visitor);
        }
        return $this->compiler->compile($ast);
    }

    public static function createWithDomain($domain, AST\Visitor $visitor = null) {
        $sa = new SA\SemanticAnalysis([
            new SA\EnforceSimpleExpressions(),
            new SA\EnforceDomain($domain)
        ]);
        return new self(null, $sa, null, $visitor);
    }

    public static function createSort(AST\Visitor $visitor = null) {
        return new self(
            new Parser\SortParser(),
            new SA\SemanticAnalysis([]),
            new Compiler\SortCompiler(),
            $visitor
        );
    }

    public static function createSortWithDomain($domain, AST\Visitor $visitor = null) {
        return new self(
            new Parser\SortParser(),
            new SA\SemanticAnalysis([new SA\EnforceDomain($domain)]),
            new Compiler\SortCompiler(),
            $visitor
        );
    }
}
