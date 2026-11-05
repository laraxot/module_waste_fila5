<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Autorizzazioni impianto',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-shield-check',
        'sort' => 40,
    ],
    'label' => 'Autorizzazione',
    'plural_label' => 'Autorizzazioni impianto',
    'plural' => ['label' => 'Autorizzazioni impianto'],
    'fields' => [
        'operator_site_id' => ['label' => 'Impianto'],
        'site' => [
            'name' => ['label' => 'Impianto'],
        ],
        'type' => ['label' => 'Tipo di titolo'],
        'number' => ['label' => 'Numero atto'],
        'issuing_authority' => ['label' => 'Autorità competente'],
        'issued_at' => ['label' => 'Data di rilascio'],
        'expires_at' => [
            'label' => 'Data di scadenza',
            'helper_text' => 'Vuota se il titolo non ha scadenza. La data è inclusa nel periodo di validità.',
        ],
        'is_active' => ['label' => 'Attiva'],
        'notes' => ['label' => 'Note'],
        'document' => [
            'label' => 'Atto autorizzativo',
            'helper_text' => 'PDF o file firmato digitalmente (.p7m).',
        ],
        'authorized_wastes_count' => ['label' => 'Codici autorizzati'],
        'valid_today' => ['label' => 'Valide oggi'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuova autorizzazione'],
        'view' => ['label' => 'Dettaglio'],
        'edit' => ['label' => 'Modifica'],
    ],
];
