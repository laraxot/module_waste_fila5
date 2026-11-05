<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

/**
 * Modifica non ammessa di un formulario già emesso (stato diverso da `draft`).
 *
 * Dopo la bozza cambiano solo lo stato (lungo le transizioni di FirStatusEnum), le date di
 * partenza/arrivo, l'esito dell'accettazione e il numero assegnato da RENTRI: vedi Fir::booted().
 */
final class ImmutableFirException extends WasteDomainException
{
    /**
     * @param  list<string>  $fields
     */
    public static function fields(string $fir, array $fields): self
    {
        return new self(self::message('immutable_fir.fields', ['fir' => $fir, 'fields' => implode(', ', $fields)]));
    }

    public static function status(string $fir, string $from, string $to): self
    {
        return new self(self::message('immutable_fir.status', ['fir' => $fir, 'from' => $from, 'to' => $to]));
    }

    public static function deletion(string $fir): self
    {
        return new self(self::message('immutable_fir.deletion', ['fir' => $fir]));
    }
}
