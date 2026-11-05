<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Causale della scrittura sul registro. Il codice causale RENTRI corrispondente NON sta qui:
 * lo mappa il modulo Rentri.
 */
enum RegisterCauseEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use EnumTrait;

    /** Prodotto dall'attività (carico). */
    case NEW_PRODUCTION = 'new_production';
    /** Ricevuto da terzi (carico). */
    case FROM_THIRD_PARTIES = 'from_third_parties';
    /** Consegnato a terzi (scarico). */
    case TO_THIRD_PARTIES = 'to_third_parties';
    /** Avviato a trattamento interno (scarico). */
    case INTERNAL_TREATMENT = 'internal_treatment';
    /** Respinto (carico del rientro presso il produttore o scarico della quota respinta). */
    case REJECTED_RETURN = 'rejected_return';
    /** Rettifica di giacenza (carico o scarico). */
    case ADJUSTMENT = 'adjustment';

    /**
     * @return list<RegisterEntryTypeEnum>
     */
    public function allowedTypes(): array
    {
        return match ($this) {
            self::NEW_PRODUCTION, self::FROM_THIRD_PARTIES => [RegisterEntryTypeEnum::LOAD],
            self::TO_THIRD_PARTIES, self::INTERNAL_TREATMENT => [RegisterEntryTypeEnum::UNLOAD],
            self::REJECTED_RETURN, self::ADJUSTMENT => [RegisterEntryTypeEnum::LOAD, RegisterEntryTypeEnum::UNLOAD],
        };
    }

    public function allowsType(RegisterEntryTypeEnum $type): bool
    {
        return in_array($type, $this->allowedTypes(), true);
    }

    /** Le movimentazioni verso un trattamento (terzi o interno) indicano l'operazione R/D di destinazione. */
    public function requiresOperation(): bool
    {
        return $this === self::TO_THIRD_PARTIES || $this === self::INTERNAL_TREATMENT;
    }

    /** Le movimentazioni con terzi indicano il soggetto controparte (produttore o destinatario). */
    public function requiresCounterpart(): bool
    {
        return $this === self::FROM_THIRD_PARTIES || $this === self::TO_THIRD_PARTIES;
    }
}
