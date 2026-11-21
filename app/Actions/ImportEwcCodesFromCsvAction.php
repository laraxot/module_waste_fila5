<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Waste\Exceptions\EwcCsvImportException;
use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Modules\Waste\Models\EwcCode;
use Spatie\QueueableAction\QueueableAction;

use function Safe\fclose;
use function Safe\fgetcsv;
use function Safe\fopen;
use function Safe\mb_convert_encoding;
use function Safe\preg_replace;

/**
 * Importa (o aggiorna) il catalogo EER da un CSV con separatore `;` e colonne
 * `code;description;is_hazardous;is_mirror`. La riga di intestazione è facoltativa.
 *
 * - `code` in qualunque formato accettato da NormalizeEwcCodeAction; l'asterisco finale marca il codice
 *   come pericoloso anche se `is_hazardous` è vuoto;
 * - booleani: 1/0, true/false, si/no, yes/no, x, * (maiuscole indifferenti, vuoto = falso);
 * - testo in UTF-8 o Windows-1252 (export usuali dei fogli di calcolo).
 *
 * Tutto o niente: alla prima riga non valida l'import si interrompe e nulla viene salvato.
 * Restituisce il numero di codici importati o aggiornati.
 */
class ImportEwcCodesFromCsvAction
{
    use QueueableAction;

    private const array TRUE_VALUES = ['1', 'true', 'si', 'sì', 's', 'yes', 'y', 'x', '*'];

    public function __construct(
        private readonly NormalizeEwcCodeAction $normalize,
    ) {}

    public function execute(string $path): int
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw EwcCsvImportException::unreadable($path);
        }

        $handle = fopen($path, 'rb');

        try {
            return DB::connection((new EwcCode)->getConnectionName())
                ->transaction(function () use ($handle): int {
                    $count = 0;
                    $line = 0;
                    while (($row = fgetcsv($handle, null, ';', '"', '')) !== false) {
                        $line++;
                        /** @var array<int, string> $cells */
                        $cells = [];
                        foreach ($row as $cell) {
                            $cells[] = $this->utf8(is_string($cell) ? trim($cell) : '');
                        }
                        if ($line === 1) {
                            $first = preg_replace('/^\xEF\xBB\xBF/', '', $cells[0] ?? '');
                            $cells[0] = is_string($first) ? $first : '';
                            if (mb_strtolower($cells[0]) === 'code') {
                                continue;
                            }
                        }
                        if (implode('', $cells) === '') {
                            continue;
                        }

                        $this->importRow($cells, $line);
                        $count++;
                    }

                    return $count;
                });
        } finally {
            fclose($handle);
        }
    }

    /**
     * @param  array<int, string>  $cells
     */
    private function importRow(array $cells, int $line): void
    {
        $rawCode = $cells[0] ?? '';
        $description = $cells[1] ?? '';

        try {
            $code = $this->normalize->execute($rawCode);
        } catch (InvalidEwcCodeException $exception) {
            throw EwcCsvImportException::invalidRow($line, $exception->getMessage());
        }

        if ($description === '') {
            throw EwcCsvImportException::invalidRow($line, 'description');
        }

        EwcCode::query()->updateOrCreate(
            ['code' => $code],
            [
                'description' => $description,
                'is_hazardous' => str_contains($rawCode, '*') || $this->toBool($cells[2] ?? ''),
                'is_mirror' => $this->toBool($cells[3] ?? ''),
                'is_active' => true,
            ],
        );
    }

    private function toBool(string $value): bool
    {
        return in_array(mb_strtolower($value), self::TRUE_VALUES, true);
    }

    private function utf8(string $value): string
    {
        if ($value === '' || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $converted = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');

        return is_string($converted) ? $converted : $value;
    }
}
