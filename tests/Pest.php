<?php

use LumenSistemas\BrValidation\Tests\TestCase;

pest()
->extend(TestCase::class)
->in('Feature');

function failClosure(bool &$failed): Closure
{
    return function () use (&$failed): object {
        $failed = true;

        return new class() {
            public function translate(): void {}
        };
    };
}
