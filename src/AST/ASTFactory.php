<?php

namespace Krak\AQL\AST;

use Krak\Lex,
    Krak\AQL\Parser\ExpressionParser;

/** utility methods for generating AST nodes */
class ASTFactory
{
    public function createStringValue($s) {
        $s = '"' . $s . '"';
        return Value::string(new Lex\MatchedToken($s, ExpressionParser::TOK_STRING, 0));
    }
    public function createNumberValue($n) {
        return Value::number(new Lex\MatchedToken($n, ExpressionParser::TOK_NUMBER, 0));
    }
}
