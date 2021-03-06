<?php

namespace Krak\AQL\Parser;

class ExpressionParser implements Parser
{
    private $parser;

    public function __construct(AQLParser $parser = null) {
        $this->parser = $parser ?: new AQLParser();
    }

    public function parse($input) {
        return $this->parser->parse($input, function($parser, $stream) {
            return $parser->parseExpression($stream);
        });
    }
}
