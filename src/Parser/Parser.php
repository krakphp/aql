<?php

namespace Krak\AQL\Parser;

interface Parser
{
    /** @return AST\Expression */
    public function parse($input);
}
