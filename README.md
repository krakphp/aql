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

## Visitors

Visitors provide a way to transform the AST. In context of the engine, visitors are run *after* semantic analysis. Each visitor implements `Krak\AQL\AST\Visitor`. The AST accepts the visitor and then traverses itself with a [Depth First Pre-Order Traversal](https://en.wikipedia.org/wiki/Tree_traversal#Depth-first_search).

### Chain Visitor

The chain visitor `Krak\AQL\AST\ChainVisitor` accepts an array of other visitors. As it the AST is being traversed, it delegates to each of the other visitors. This allows for many transformations in one Pass.

### DoubleToSingleQuotes Visitor

This visitor `Krak\AQL\Visitor\DoubleToSingleQuotesVisitor` transforms double quoted strings to single quotes.

So this input:

```
"string"
```

would be be mapped to:

```
'string'
```

### RenameId Visitor

This visitor `Krak\AQL\Visitor\RenameIdVisitor` renames top level identifiers.

```php
$ast->accept(new AQL\Visitor\RenameIdVisitor([
    'a' => 'alpha',
    'b' => 'beta'
]));
```

Using this rename visitor, it'd apply the following transformation:

```
a = b.a
```

Goes to:

```
alpha = beta.a
```

## Parser

### Operators

From highest to lowest precedence

```
()
< = > <= >= IN
AND
OR
```

### EBNF (Grammar)

    Expression    ::= AndExpression | AndExpression "OR" Expression
    AndExpression ::= OpExpression | OpExression "AND" AndExpression
    OpExpression  ::= Element
    OpExpression  ::= Element "<" OpExpression
    OpExpression  ::= Element ">" OpExpression
    OpExpression  ::= Element "=" OpExpression
    OpExpression  ::= Element "!=" OpExpression
    OpExpression  ::= Element "<=" OpExpression
    OpExpression  ::= Element ">=" OpExpression
    OpExpression  ::= Element "IN" "(" ValueList ")"
    Element       ::= Value | IdExpression | "(" Expression ")"
    Value         ::= string | number
    ValueList     ::= Value | Value "," ValueList
    IdExpression  ::= identifier | identifier "." IdExpression

    string     = "[^"]\*"
    number     = (\d*\.\d+|\d+)
    identifier = [_a-zA-Z][_a-zA-Z0-9]*
