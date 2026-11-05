<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Ciclo di vita del formulario di identificazione del rifiuto (art. 193 D.Lgs. 152/2006).
 *
 * draft → issued → in_transit → delivered → accepted | partially_accepted | rejected
 * draft | issued → cancelled
 * Gli esiti di accettazione e l'annullamento sono stati finali.
 */
enum FirStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case DRAFT = 'draft';
    case ISSUED = 'issued';
    case IN_TRANSIT = 'in_transit';
    case DELIVERED = 'delivered';
    case ACCEPTED = 'accepted';
    case PARTIALLY_ACCEPTED = 'partially_accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::ISSUED, self::CANCELLED],
            self::ISSUED => [self::IN_TRANSIT, self::CANCELLED],
            self::IN_TRANSIT => [self::DELIVERED],
            self::DELIVERED => [self::ACCEPTED, self::PARTIALLY_ACCEPTED, self::REJECTED],
            self::ACCEPTED, self::PARTIALLY_ACCEPTED, self::REJECTED, self::CANCELLED => [],
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTransitions(), true);
    }

<<<<<<< HEAD
=======
    /**
     * Stato raggiungibile con uno o più passaggi consentiti (es. `issued` → `accepted` passando da
     * `in_transit` e `delivered`, come fa RecordFirAcceptanceAction in un solo salvataggio).
     */
    public function canReach(self $to): bool
    {
        $visited = [];
        $queue = $this->allowedTransitions();
        while ($queue !== []) {
            $next = array_shift($queue);
            if ($next === $to) {
                return true;
            }
            if (in_array($next, $visited, true)) {
                continue;
            }
            $visited[] = $next;
            array_push($queue, ...$next->allowedTransitions());
        }

        return false;
    }

>>>>>>> laraxot/dev
    public function isFinal(): bool
    {
        return $this->allowedTransitions() === [];
    }

    /** Stati raggiungibili solo registrando l'accettazione (con quantità accettata). */
    public function isAcceptanceOutcome(): bool
    {
        return in_array($this, [self::ACCEPTED, self::PARTIALLY_ACCEPTED, self::REJECTED], true);
    }

    /** Stati con cui un FIR può essere creato (inserimento di un formulario cartaceo già in viaggio). */
    public function isInitial(): bool
    {
        return in_array($this, [self::DRAFT, self::ISSUED, self::IN_TRANSIT], true);
    }
}
