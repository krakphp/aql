<?php

use Krak\AQL\Parser;

describe('ExpressionParser', function() {
    describe('::createLexer', function() {
        it('creates a lexer for parsing AQL', function() {
            $input = '0a b._c<><=>==(!=) andy more and or 0. .0 0.0 ""like likely';
            $lex = Parser\ExpressionParser::createLexer();
            $stream = $lex($input);

            $tok_sequence = [
                Parser\ExpressionParser::TOK_NUMBER,
                Parser\ExpressionParser::TOK_ID,
                Parser\ExpressionParser::TOK_ID,
                Parser\ExpressionParser::TOK_DOT,
                Parser\ExpressionParser::TOK_ID,
                Parser\ExpressionParser::TOK_LT,
                Parser\ExpressionParser::TOK_GT,
                Parser\ExpressionParser::TOK_LTE,
                Parser\ExpressionParser::TOK_GTE,
                Parser\ExpressionParser::TOK_EQ,
                Parser\ExpressionParser::TOK_LPAREN,
                Parser\ExpressionParser::TOK_NEQ,
                Parser\ExpressionParser::TOK_RPAREN,
                Parser\ExpressionParser::TOK_ID,
                Parser\ExpressionParser::TOK_ID,
                Parser\ExpressionParser::TOK_AND,
                Parser\ExpressionParser::TOK_OR,
                Parser\ExpressionParser::TOK_NUMBER,
                Parser\ExpressionParser::TOK_DOT,
                Parser\ExpressionParser::TOK_NUMBER,
                Parser\ExpressionParser::TOK_NUMBER,
                Parser\ExpressionParser::TOK_STRING,
                Parser\ExpressionParser::TOK_LIKE,
                Parser\ExpressionParser::TOK_ID,
            ];

            $tok = $tok_sequence[0];
            while (!$stream->isEmpty()) {
                assert($stream->getToken()->token == $tok);
                $tok = next($tok_sequence);
            }
        });
    });
});
