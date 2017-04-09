<?php

namespace Krak\AQL\Parser;

interface Parser
{
    /** @return AST\Node */
    public function parse($input);
}
