<?php

namespace Krak\AQL\Parser;

use Krak\Lex;

class PutBackTokenStream implements Lex\TokenStream
{
    private $stream;
    private $tok;

    public function __construct(Lex\TokenStream $stream, $tok) {
        $this->stream = $stream;
        $this->tok = $tok;
    }

    public function getToken() {
        if ($this->tok) {
            $tok = $this->tok;
            $this->tok = null;
            return $tok;
        }
        return $this->stream->getToken();
    }

    public function peek() {
        return $this->stream->peek();
    }

    public function isEmpty() {
        return $this->stream->isEmpty() && $this->tok === null;
    }

    public function getIterator() {
        return $this->stream->getIterator();
    }
}
