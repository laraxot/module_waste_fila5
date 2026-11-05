<?php

declare(strict_types=1);

namespace Modules\Waste\Actions;

use Modules\Waste\Exceptions\InvalidEwcCodeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Normalizza un codice EER nel formato canonico `CC SS VV` (capitolo, sottocapitolo, voce: `19 12 04`).
 *
 * Accetta gli spazi, i punti, i trattini e le barre usati nei documenti (`191204`, `19.12.04`, `19-12-04`)
 * e l'asterisco finale dei codici pericolosi (`16 01 07*`), che viene rimosso: la pericolosità è un attributo
 * della voce (`EwcCode::is_hazardous`), non parte del codice. Capitoli ammessi: da 01 a 20.
 */
class NormalizeEwcCodeAction
{
    use QueueableAction;

    public function execute(string $raw): string
    {
        $value = rtrim(trim($raw), "* \t");
        $digits = preg_replace('/[\s.\-\/]+/', '', $value);

        if (! is_string($digits) || preg_match('/^\d{6}$/', $digits) !== 1) {
            throw InvalidEwcCodeException::malformed($raw);
        }

        $chapter = substr($digits, 0, 2);
        $subChapter = substr($digits, 2, 2);
        $entry = substr($digits, 4, 2);

        $chapterNumber = (int) $chapter;
        if ($chapterNumber < 1 || $chapterNumber > 20) {
            throw InvalidEwcCodeException::chapterOutOfRange($raw);
        }

        if ($subChapter === '00' || $entry === '00') {
            throw InvalidEwcCodeException::malformed($raw);
        }

        return $chapter.' '.$subChapter.' '.$entry;
    }
}
