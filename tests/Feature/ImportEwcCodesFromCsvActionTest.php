<?php

declare(strict_types=1);

use Modules\Waste\Actions\ImportEwcCodesFromCsvAction;
use Modules\Waste\Exceptions\EwcCsvImportException;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function ewcCsv(string $content): string
{
    $path = tempnam(sys_get_temp_dir(), 'ewc_');
    if ($path === false) {
        throw new RuntimeException('tempnam failed');
    }
    file_put_contents($path, $content);

    return $path;
}

describe('ImportEwcCodesFromCsvAction', function (): void {
    test('imports and updates codes, accepting header, asterisk and Windows-1252 text', function (): void {
        // Codici fittizi (sottocapitolo 9x) per non toccare il catalogo reale del DB di test.
        $csv = "\xEF\xBB\xBFcode;description;is_hazardous;is_mirror\n"
            ."19 91 01;prova uno;0;0\n"
            ."199102*;prova due pericolosa;;si\n"
            ."19.91.03;".mb_convert_encoding('prova tre è', 'Windows-1252', 'UTF-8').";no;1\n"
            ."\n";
        $path = ewcCsv($csv);

        $count = app(ImportEwcCodesFromCsvAction::class)->execute($path);

        Assert::assertSame(3, $count);
        $two = EwcCode::query()->where('code', '19 91 02')->firstOrFail();
        Assert::assertTrue($two->is_hazardous);
        Assert::assertTrue($two->is_mirror);
        Assert::assertSame('19 91', $two->sub_chapter);
        Assert::assertSame('prova tre è', EwcCode::query()->where('code', '19 91 03')->value('description'));

        $again = app(ImportEwcCodesFromCsvAction::class)->execute(ewcCsv("19 91 01;descrizione aggiornata;1;0\n"));
        Assert::assertSame(1, $again);
        Assert::assertSame('descrizione aggiornata', EwcCode::query()->where('code', '19 91 01')->value('description'));
        Assert::assertSame(1, EwcCode::query()->where('code', '19 91 01')->count());
    });

    test('is all or nothing on an invalid row', function (): void {
        $path = ewcCsv("19 92 01;valida;0;0\n21 01 01;capitolo inesistente;0;0\n");

        try {
            app(ImportEwcCodesFromCsvAction::class)->execute($path);
            Assert::fail('exception expected');
        } catch (EwcCsvImportException) {
            Assert::assertFalse(EwcCode::query()->where('code', '19 92 01')->exists());
        }
    });

    test('rejects an unreadable file', function (): void {
        app(ImportEwcCodesFromCsvAction::class)->execute('/nonexistent/ewc.csv');
    })->throws(EwcCsvImportException::class);
});
