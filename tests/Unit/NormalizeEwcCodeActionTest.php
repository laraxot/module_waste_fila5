<?php

declare(strict_types=1);

use Modules\Waste\Actions\NormalizeEwcCodeAction;
use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('NormalizeEwcCodeAction', function (): void {
    test('normalizes the usual EWC notations to "CC SS VV"', function (string $raw, string $expected): void {
        Assert::assertSame($expected, app(NormalizeEwcCodeAction::class)->execute($raw));
    })->with([
        'canonical' => ['19 12 04', '19 12 04'],
        'compact' => ['191204', '19 12 04'],
        'dots' => ['19.12.04', '19 12 04'],
        'dashes' => ['19-12-04', '19 12 04'],
        'hazardous asterisk' => ['16 01 07*', '16 01 07'],
        'asterisk without spaces' => ['160107*', '16 01 07'],
        'surrounding blanks' => ["  20 03 01 \t", '20 03 01'],
        'first chapter' => ['01 01 01', '01 01 01'],
        'last chapter' => ['20 01 40', '20 01 40'],
    ]);

    test('rejects malformed codes', function (string $raw): void {
        app(NormalizeEwcCodeAction::class)->execute($raw);
    })->with([
        'empty' => [''],
        'too short' => ['19 12'],
        'too long' => ['19 12 04 01'],
        'letters' => ['19 AB 04'],
        'zero sub-chapter' => ['19 00 04'],
        'zero entry' => ['19 12 00'],
    ])->throws(InvalidEwcCodeException::class);

    test('rejects chapters outside 01-20', function (string $raw): void {
        app(NormalizeEwcCodeAction::class)->execute($raw);
    })->with([
        'chapter 00' => ['00 01 01'],
        'chapter 21' => ['21 01 01'],
        'chapter 99' => ['99 99 99'],
    ])->throws(InvalidEwcCodeException::class);
});
