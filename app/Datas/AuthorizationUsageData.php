<?php

declare(strict_types=1);

namespace Modules\Waste\Datas;

use Spatie\LaravelData\Data;

/**
 * Utilizzo dei limiti di un codice autorizzato: giacenza istantanea rispetto a `max_storage_kg`
 * e carichi dell'anno solare rispetto a `max_annual_kg`. Le percentuali sono null se il limite non è fissato.
 */
final class AuthorizationUsageData extends Data
{
    public function __construct(
        public float $currentStockKg,
        public ?float $maxStorageKg,
        public ?float $storageUsagePct,
        public float $annualLoadedKg,
        public ?float $maxAnnualKg,
        public ?float $annualUsagePct,
    ) {}

    /** Kg ancora stoccabili prima di raggiungere il limite istantaneo (null = senza limite). */
    public function remainingStorageKg(): ?float
    {
        return $this->maxStorageKg === null ? null : round($this->maxStorageKg - $this->currentStockKg, 3);
    }

    /** Kg ancora caricabili nell'anno (null = senza limite). */
    public function remainingAnnualKg(): ?float
    {
        return $this->maxAnnualKg === null ? null : round($this->maxAnnualKg - $this->annualLoadedKg, 3);
    }

    public function isStorageExceeded(): bool
    {
        return $this->maxStorageKg !== null && $this->currentStockKg > $this->maxStorageKg;
    }

    public function isAnnualExceeded(): bool
    {
        return $this->maxAnnualKg !== null && $this->annualLoadedKg > $this->maxAnnualKg;
    }
}
