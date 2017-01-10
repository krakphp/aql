<?php

describe('Krak AQL', function() {
    describe('Parser', function() {
        require __DIR__ . '/parser.php';
    });
    describe('Visitor', function() {
        require __DIR__ . '/visitor.php';
    });
});
