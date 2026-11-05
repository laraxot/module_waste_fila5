<?php

declare(strict_types=1);

return [
    'values' => [
        'draft' => ['label' => 'Bozza', 'color' => 'gray', 'icon' => 'heroicon-o-pencil-square'],
        'issued' => ['label' => 'Emesso', 'color' => 'info', 'icon' => 'heroicon-o-document-text'],
        'in_transit' => ['label' => 'In trasporto', 'color' => 'warning', 'icon' => 'heroicon-o-truck'],
        'delivered' => ['label' => 'Consegnato', 'color' => 'primary', 'icon' => 'heroicon-o-inbox-arrow-down'],
        'accepted' => ['label' => 'Accettato', 'color' => 'success', 'icon' => 'heroicon-o-check-circle'],
        'partially_accepted' => ['label' => 'Accettato parzialmente', 'color' => 'warning', 'icon' => 'heroicon-o-check-circle'],
        'rejected' => ['label' => 'Respinto', 'color' => 'danger', 'icon' => 'heroicon-o-x-circle'],
        'cancelled' => ['label' => 'Annullato', 'color' => 'gray', 'icon' => 'heroicon-o-x-circle'],
    ],
];
