<?php

declare(strict_types=1);

/*
 * Causali del registro. Il codice causale RENTRI corrispondente è mappato dal modulo Rentri.
 */
return [
    'values' => [
        'new_production' => ['label' => 'Nuova produzione', 'description' => 'Rifiuto prodotto dall\'attività (carico).', 'color' => 'primary', 'icon' => 'heroicon-o-sparkles'],
        'from_third_parties' => ['label' => 'Ricevuto da terzi', 'description' => 'Rifiuto ricevuto da un altro soggetto (carico).', 'color' => 'success', 'icon' => 'heroicon-o-arrow-down-tray'],
        'to_third_parties' => ['label' => 'Consegnato a terzi', 'description' => 'Rifiuto consegnato a un altro soggetto per recupero o smaltimento (scarico).', 'color' => 'warning', 'icon' => 'heroicon-o-arrow-up-tray'],
        'internal_treatment' => ['label' => 'Avviato a trattamento interno', 'description' => 'Rifiuto avviato a trattamento nell\'impianto (scarico).', 'color' => 'info', 'icon' => 'heroicon-o-wrench'],
        'rejected_return' => ['label' => 'Respinto', 'description' => 'Rifiuto respinto dal destinatario (carico del rientro o scarico della quota respinta).', 'color' => 'danger', 'icon' => 'heroicon-o-x-circle'],
        'adjustment' => ['label' => 'Rettifica di giacenza', 'description' => 'Allineamento della giacenza registrata a quella reale (carico o scarico).', 'color' => 'gray', 'icon' => 'heroicon-o-scale'],
    ],
];
