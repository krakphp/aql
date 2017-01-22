<?php

namespace Krak\AQL\Parser;

use Krak\AQL,
    Krak\Lex;

class ExpressionParser implements Parser
{
    const TOK_OR = 'or';
    const TOK_AND = 'and';
    const TOK_IN = 'in';
    const TOK_LIKE = 'like';
    const TOK_LT = '<';
    const TOK_LTE = '<=';
    const TOK_GT = '>';
    const TOK_GTE = '>=';
    const TOK_EQ = '=';
    const TOK_NEQ = '!=';
    const TOK_STRING = 'string';
    const TOK_NUMBER = 'number';
    const TOK_LPAREN = '(';
    const TOK_RPAREN = ')';
    const TOK_ID = 'id';
    const TOK_DOT = '.';
    const TOK_COMMA = ',';
    const TOK_WS = 'whitespace';

    private $lex;

    public function __construct($lex = null) {
        $this->lex = $lex ?: self::createLexer();
    }

    public function parse($input) {
        $lex = $this->lex;
        $stream = $lex($input);

        try {
            return $this->parseExpression($stream);
        } catch (Lex\LexException $e) {
            throw new ParseException($e->getMessage());
        }
    }

    public function parseExpression($stream) {
        $left = $this->parseAndExpression($stream);
        $right = null;

        if (!$stream->isEmpty() && $stream->peek()->token == self::TOK_OR) {
            $stream->getToken();
            $right = $this->parseExpression($stream);
        }

        return new AQL\AST\Expression($left, $right);
    }

    public function parseAndExpression($stream) {
        $left = $this->parseOpExpression($stream);
        $right = null;

        if (!$stream->isEmpty() && $stream->peek()->token == self::TOK_AND) {
            $stream->getToken();
            $right = $this->parseAndExpression($stream);
        }

        return new AQL\AST\AndExpression($left, $right);
    }

    public function parseOpExpression($stream) {
        $left = $this->parseElement($stream);
        $op = null;
        $right = null;
        $value_list = null;

        if ($stream->isEmpty()) {
            return new AQL\AST\OpExpression($left);
        }

        $tok = $stream->peek();

        switch ($tok->token) {
        case self::TOK_LT:
            $op = AQL\AST\Operators::OP_LT;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_LTE:
            $op = AQL\AST\Operators::OP_LTE;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_GT:
            $op = AQL\AST\Operators::OP_GT;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_GTE:
            $op = AQL\AST\Operators::OP_GTE;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_EQ:
            $op = AQL\AST\Operators::OP_EQ;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_NEQ:
            $op = AQL\AST\Operators::OP_NEQ;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_LIKE:
            $op = AQL\AST\Operators::OP_LIKE;
            $stream->getToken();
            $right = $this->parseOpExpression($stream);
            break;
        case self::TOK_IN:
            $op = AQL\AST\Operators::OP_IN;
            $stream->getToken();
            $this->expect($stream, self::TOK_LPAREN);
            $value_list = $this->parseValueList($stream);
            $this->expect($stream, self::TOK_RPAREN);
        }

        return new AQL\AST\OpExpression($left, $op, $right, $value_list);
    }

    public function parseValueList($stream) {
        $value = $this->parseValue($stream);
        $right = null;

        $tok = $stream->peek();
        if ($tok && $tok->token == self::TOK_COMMA) {
            $stream->getToken();
            $right = $this->parseValueList($stream);
        }

        return new AQL\AST\ValueList($value, $right);
    }

    public function parseValue($stream) {
        $tok = $this->expect($stream, [self::TOK_STRING, self::TOK_NUMBER]);

        if ($tok->token == self::TOK_STRING) {
            return AQL\AST\Value::string($tok);
        }
        else if ($tok->token == self::TOK_NUMBER) {
            return AQL\AST\Value::number($tok);
        }
    }

    public function parseElement($stream) {
        $tok = $this->expect($stream, [self::TOK_STRING, self::TOK_NUMBER, self::TOK_LPAREN, self::TOK_ID], true);

        if ($tok->token == self::TOK_STRING || $tok->token == self::TOK_NUMBER) {
            return AQL\AST\Element::value($this->parseValue($stream));
        }
        if ($tok->token == self::TOK_LPAREN) {
            $stream->getToken();
            $expr = $this->parseExpression($stream);
            $this->expect($stream, self::TOK_RPAREN);
            return AQL\AST\Element::expr($expr);
        }

        return AQL\AST\Element::id($this->parseIdExpression($stream));
    }

    public function parseIdExpression($stream) {
        $id = $this->expect($stream, self::TOK_ID);
        $right = null;

        if (!$stream->isEmpty() && $stream->peek()->token == self::TOK_DOT) {
            $stream->getToken();
            $right = $this->parseIdExpression($stream);
        }

        return new AQL\AST\IdExpression($id, $right);
    }

    private function expect($stream, $expected_tok, $peek = false) {
        if ($peek) {
            $tok = $stream->peek();
        } else {
            $tok = $stream->getToken();
        }

        if (!$tok) {
            throw new ParseException(sprintf("Expected token %s but got EOF", $this->prepareExpectedTokenMessage($expected_tok)));
        }
        if (!is_array($expected_tok)) {
            $expected_tok = [$expected_tok];
        }

        foreach ($expected_tok as $et) {
            if ($tok->token == $et) {
                return $tok;
            }
        }

        throw new ParseException(sprintf("Expected token %s but got '%s'", $this->prepareExpectedTokenMessage($expected_tok), $tok->token));
    }

    private function prepareExpectedTokenMessage($expected_tok) {
        if (count($expected_tok) > 1) {
            $quoted_toks = array_map(function($v) {
                return "'$v'";
            }, $expected_tok);
            $all_but_last = array_slice($quoted_toks, 0, -1);
            $msg = implode(", ", $all_but_last);
            if (count($expected_tok) > 2) {
                $msg .= ',';
            }
            $msg .= sprintf(" or %s", end($quoted_toks));
        } else {
            $msg = "'$expected_tok'";
        }

        return $msg;
    }

    public static function createLexer() {
        $lex = Lex\lexer([
            '/or(?![a-z])/iA' => self::TOK_OR,
            '/and(?![a-z])/iA' => self::TOK_AND,
            '/in(?![a-z])/iA' => self::TOK_IN,
            '/like(?![a-z])/iA' => self::TOK_LIKE,
            '/<=/A' => self::TOK_LTE,
            '/>=/A' => self::TOK_GTE,
            '/</A' => self::TOK_LT,
            '/>/A' => self::TOK_GT,
            '/=/A' => self::TOK_EQ,
            '/!=/A' => self::TOK_NEQ,
            '/\(/A' => self::TOK_LPAREN,
            '/\)/A' => self::TOK_RPAREN,
            '/"[^"]*"/A' => self::TOK_STRING,
            '/(\d*\.\d+|\d+)/A' => self::TOK_NUMBER,
            '/[_a-zA-Z][_a-zA-Z0-9]*/A' => self::TOK_ID,
            '/,/A' => self::TOK_COMMA,
            '/\./A' => self::TOK_DOT,
            '/\s+/A' => self::TOK_WS
        ]);

        $lex = Lex\skipLexer($lex, [self::TOK_WS]);
        $lex = Lex\tokenStreamLexer($lex);
        return $lex;
    }
}
