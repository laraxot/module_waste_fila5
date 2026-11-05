<?php

declare(strict_types=1);

namespace Modules\Waste\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Operazioni di recupero (Allegato C) e di smaltimento (Allegato B) alla Parte IV del D.Lgs. 152/2006.
 *
 * Etichette e descrizioni: lang/{it,en}/waste_operation_enum.php.
 */
enum WasteOperationEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use EnumTrait;

    case R1 = 'R1';
    case R2 = 'R2';
    case R3 = 'R3';
    case R4 = 'R4';
    case R5 = 'R5';
    case R6 = 'R6';
    case R7 = 'R7';
    case R8 = 'R8';
    case R9 = 'R9';
    case R10 = 'R10';
    case R11 = 'R11';
    case R12 = 'R12';
    case R13 = 'R13';
    case D1 = 'D1';
    case D2 = 'D2';
    case D3 = 'D3';
    case D4 = 'D4';
    case D5 = 'D5';
    case D6 = 'D6';
    case D7 = 'D7';
    case D8 = 'D8';
    case D9 = 'D9';
    case D10 = 'D10';
    case D11 = 'D11';
    case D12 = 'D12';
    case D13 = 'D13';
    case D14 = 'D14';
    case D15 = 'D15';

    /** Operazione di recupero (Allegato C). */
    public function isRecovery(): bool
    {
        return str_starts_with($this->value, 'R');
    }

    /** Operazione di smaltimento (Allegato B). */
    public function isDisposal(): bool
    {
        return str_starts_with($this->value, 'D');
    }

    /**
     * Messa in riserva (R13) o deposito preliminare (D15): stoccaggio in attesa di un'altra operazione,
     * escluso il deposito temporaneo nel luogo di produzione.
     */
    public function isPreliminaryStorage(): bool
    {
        return $this === self::R13 || $this === self::D15;
    }

    /** Numero progressivo dell'operazione nel proprio allegato (R12 → 12). */
    public function number(): int
    {
        return (int) substr($this->value, 1);
    }
}
