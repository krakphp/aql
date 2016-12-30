<?php

namespace Krak\AQL\Compiler;

use Krak\AQL\AST;

interface Compiler {
    public function compile(AST\Expression $expr);
}
