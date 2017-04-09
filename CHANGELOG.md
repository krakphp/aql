# Change Log

## Unreleased

### Added

- Sort Processing #10

## 0.2.1 - 2017-04-08

### Added

- FuncEval #7
- ASTFactory to help create simple AST nodes manually
- Nested rename in RenameIdVisitor #1

## 0.2.0 - 2017-01-22

### Added

- Added LIKE expression
- Added ability to parse functions and enforce them via `EnforceFunc`
- Added better documentation

## 0.1.2 - 2017-01-10

### Added

- `DoubleToSingleQuotesVisitor` and appropriate tests
- Documentation on visitors.

### Fixed

- Bug in RenameId visitor where it would rename any match instead of top level identifiers

## 0.1.1 - 2017-01-03

### Fixed

- Several parser bugs regarding unexpected input
- Several documentation bugs

### Changed

- Parser exception messaging to be more precise

## 0.1.0 - 2016-12-30

### Added

- Initial Implementation
- Added Documentation
- Added Compiler, Parser, SA, Visitor, AST modules
