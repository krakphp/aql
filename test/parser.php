<?php

use Krak\AQL\Parser;
use Krak\AQL\AST;

describe('AQLParser', function() {
    describe('::createLexer', function() {
        it('creates a lexer for parsing AQL', function() {
            $input = '0a b._c<><=>==(!=) andy more and or 0. .0 0.0 ""like likely';
            $lex = Parser\AQLParser::createLexer();
            $stream = $lex($input);

            $tok_sequence = [
                Parser\AQLParser::TOK_NUMBER,
                Parser\AQLParser::TOK_ID,
                Parser\AQLParser::TOK_ID,
                Parser\AQLParser::TOK_DOT,
                Parser\AQLParser::TOK_ID,
                Parser\AQLParser::TOK_LT,
                Parser\AQLParser::TOK_GT,
                Parser\AQLParser::TOK_LTE,
                Parser\AQLParser::TOK_GTE,
                Parser\AQLParser::TOK_EQ,
                Parser\AQLParser::TOK_LPAREN,
                Parser\AQLParser::TOK_NEQ,
                Parser\AQLParser::TOK_RPAREN,
                Parser\AQLParser::TOK_ID,
                Parser\AQLParser::TOK_ID,
                Parser\AQLParser::TOK_AND,
                Parser\AQLParser::TOK_OR,
                Parser\AQLParser::TOK_NUMBER,
                Parser\AQLParser::TOK_DOT,
                Parser\AQLParser::TOK_NUMBER,
                Parser\AQLParser::TOK_NUMBER,
                Parser\AQLParser::TOK_STRING,
                Parser\AQLParser::TOK_LIKE,
                Parser\AQLParser::TOK_ID,
            ];

            $tok = $tok_sequence[0];
            while (!$stream->isEmpty()) {
                assert($stream->getToken()->token == $tok);
                $tok = next($tok_sequence);
            }
        });
    });
});
describe('ExpressionParser', function() {
    describe('->parse', function() {
        it('can parse expressions', function() {
            $parser = new Parser\ExpressionParser();
            $expr = $parser->parse('1 = 1');
            assert($expr instanceof AST\Expression);
        });
    });
});
describe('SortParser', function() {
    describe('->parse', function() {
        it('can parse sort expression lists', function() {
            $parser = new Parser\SortParser();
            $expr = $parser->parse('id ASC, id DESC');
            assert($expr instanceof AST\SortExpressionList);
        });
    });
});
