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
$query = 'orders.created_at < date(now()) and orders.status = "cancelled"';
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

The final `$processed_query` is now validated and can be used as part of an SQL where clause or something similar. Thus, it provides a way for API's to support a powerful query interface while providing total control of the queries generated.

### Sort Queries

In addition to expression queries that can be used in database `WHERE` clause. The library also supports parsing sort expressions which might show up in a database `ORDER BY` clause.

```
<?php

use Krak\AQL;

$engine = AQL\Engine::createSortWithDomain([
    'categories' => ['sort', 'created_at']
]);
$query = 'categories.sort DESC, categories.created_at'; // defaults to ASCENDING.
try {
    $processed_query = $engine->process($query);
} catch (AQL\AQLException $e) {
    // any errors regarding data syntax or semantics will be caught here.
}
```


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

This visitor `Krak\AQL\Visitor\RenameIdVisitor` renames identifiers. You can use dot notation to access sub identifiers.

```php
$ast->accept(new AQL\Visitor\RenameIdVisitor([
    'a' => 'alpha',
    'b' => 'beta',
    'b.a' => 'attribute',
]));
```

Using this rename visitor, it'd apply the following transformation:

```
a = b.a
```

Goes to:

```
alpha = beta.attribute
```

### FuncEval Visitor

The FuncEval lets your evaluate a function and transform it into another Element value.

It can turn an input:

```
1 = id(1)
```

to:

```
1 = 1
```

Checkout [example/func-eval.php](example/func-eval.php) to see a working example.

## Semantic Analysis

Semantic Analysis provides extra validation of the parsed AST to make sure the parsed expression is semantically correct.

SA Enforcers are simply just visitors that will throw an `SAException` if the AST failed semantic analysis. SA is done via a single pass because it makes use of the `AST\ChainVisitor`.

To utilize SA, you create your list of enforces and construct the `SemanticAnalysis` object.

```php
<?php

$sa = new SA\SemanticAnalysis([$enforce]);
$sa->analyze($ast);
```

### EnforceDomain

Enforces identifiers are within a certain domain.

```php
<?php

$enforce = new SA\EnforceDomain([
    'user' => [
        'id',
        'email',
        'group' => ['id', 'name']
    ]
]);
```

This would allow any identifier path like `user.id`, `user.email`, `user.group.id` and a few others.

### EnforceFunc

Enforces only certain functions to be used.

```php
<?php

$enforce = new SA\EnforceFunc(['now', 'date']);
```

This would allow only the `now` and `date` functions to be used, anything else would throw an exception.

## Parser

There are two interfaces into the AQLParser: ExpressionParser and SortParser.

The ExpressionParser will assume a string input is an expression and parse accordingly. The SortParser will assume a string is a SortExpressionList and parse accordingly. Each Parser will return a root AST node of Expression or SortExpressionList accordingly.

### Operators

From highest to lowest precedence

```
()
< = > <= >= IN LIKE
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
    OpExpression  ::= Element "LIKE" OpExpression
    OpExpression  ::= Element "IN" "(" ValueList ")"
    Element       ::= Value | IdExpression | Func | "(" Expression ")"
    Value         ::= string | number
    ValueList     ::= Value | Value "," ValueList
    IdExpression  ::= identifier | identifier "." IdExpression
    Func          ::= identifier "(" ElementList ")"
    ElementList   ::= Element | Element "," ElementList

    SortExpressionList ::= SortExpression | SortExpression "," SortExpressionList
    SortExpression     ::= IdExpression "DESC"
    SortExpression     ::= IdExpression "ASC"
    SortExpression     ::= IdExpression

    string     = "[^"]\*"
    number     = (\d*\.\d+|\d+)
    identifier = [_a-zA-Z][_a-zA-Z0-9]*

## Tests

Tests are executed via [Peridot](http://peridot-php.github.io)

```
make test
```
