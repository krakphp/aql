# AQL - API Query Language

API Query Language is a library designed to validate/transform string expressions to be used in API's for querying/filtering data. It allows user generated expressions to be used for querying data sets yet allows full verification to prevent any unwanted expression types.

## Installation

Install with composer at `krak/aql`.

## Usage

An example might be the easiest way to understand what this library does.

```php
<?php

use Krak\AQL;

$engine = AQL\Engine::createWithDomain([
    'orders' => ['status', 'created_at']
]);
$query = 'orders.created_at < "2016-12-25" and orders.status = "cancelled"';
try {
    $processed_query = $engine->process($query);
} catch (AQL\AQLException $e) {
    // any errors regarding data syntax or semantics will be caught here.
}
```

The `AQL\Engine::process` does several things.

1. It parses the expression into an AST. Any syntax or lexing errors will be thrown here. This includes bad characters or an invalid expression.
2. It runs semantic analysis on the generated AST which will verify that the expression makes sense and can enforce any custom domain rules. In the example above, it makes sure the fields being compared are within the `orders.{status,created_at}` domain.
3. It runs any custom transformations
4. It then compiles the AST back into a string to be used for generating queries.

The final processed_query is now validated and can be used as part of an SQL where clause or something similar.

## Operators

From highest to lowest precedence

```
()
< = > <= >=
AND
OR
```

## EBNF (Grammar)

    Expression    ::= AndExpression | AndExpression "OR" Expression
    AndExpression ::= OpExpression | OpExression "AND" AndExpression
    OpExpression  ::= Element
    OpExpression  ::= Element "<" OpExpression
    OpExpression  ::= Element ">" OpExpression
    OpExpression  ::= Element "=" OpExpression
    OpExpression  ::= Element "!=" OpExpression
    OpExpression  ::= Element "<=" OpExpression
    OpExpression  ::= Element ">=" OpExpression
    Element       ::= string | number | IdExpression | "(" Expression ")"
    IdExpression  ::= identifier | identifier "." IdExpression

    string     = "[^"]\*"
    number     = (\d*\.\d+|\d+)
    identifier = [_a-zA-Z][_a-zA-Z0-9]*
