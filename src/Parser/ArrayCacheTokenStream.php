<?php

namespace Krak\AQL\Parser;

use Krak\Lex;

class ArrayCacheTokenStream implements Lex\TokenStream
{
    private $stream;
    private $tok_cache = [];

    public function __construct(Lex\TokenStream $stream) {
        $this->stream = $stream;
    }

    public function getToken() {
        if (count($this->tok_cache)) {
            $tok = array_shift($this->tok_cache);
            return $tok;
        }
        return $this->stream->getToken();
    }

    public function peek() {
        $tok = $this->stream->getToken();
        array_push($this->tok_cache, $tok);
        return $tok;
    }

    public function putBack() {
        $last_tok = array_pop($this->tok_cache);
        $this->stream = new PutBackTokenStream($this->stream, $last_tok);
    }

    public function isEmpty() {
        return $this->stream->isEmpty() && count($this->tok_cache) === 0;
    }

    public function getIterator() {
        return $this->stream->getIterator();
    }
}
