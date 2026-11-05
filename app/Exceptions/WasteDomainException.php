<?php

declare(strict_types=1);

namespace Modules\Waste\Exceptions;

/**
 * Violazione di una regola del dominio rifiuti. Il messaggio è già tradotto (lang/{it,en}/exceptions.php),
 * quindi può essere mostrato all'operatore così com'è.
 */
abstract class WasteDomainException extends \DomainException
{
    /**
     * @param  array<string, string|int|float>  $params
     */
    protected static function message(string $key, array $params = []): string
    {
        $translated = __('waste::exceptions.'.$key, $params);

        return is_string($translated) ? $translated : $key;
    }
}
