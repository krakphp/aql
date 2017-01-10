# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added

- `DoubleToSingleQuotesVisitor` and appropriate tests
- Documentation on visitors.

### Fixed

- Bug in RenameId visitor where it would rename any match instead of top level identifiers

## [0.1.1] - 2017-01-03
### Fixed

- Several parser bugs regarding unexpected input
- Several documentation bugs

### Changed

- Parser exception messaging to be more precise

## [0.1.0] - 2016-12-30
### Added

- Initial Implementation
- Added Documentation
- Added Compiler, Parser, SA, Visitor, AST modules
