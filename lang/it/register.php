<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Registri di carico e scarico',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-book-open',
        'sort' => 60,
    ],
    'label' => 'Registro',
    'plural_label' => 'Registri di carico e scarico',
    'plural' => ['label' => 'Registri di carico e scarico'],
    'fields' => [
        'operator_site_id' => ['label' => 'Unità locale'],
        'site' => [
            'name' => ['label' => 'Unità locale'],
        ],
        'name' => ['label' => 'Denominazione'],
        'rentri_identifier' => [
            'label' => 'Identificativo registro RENTRI',
            'helper_text' => 'Identificativo del registro vidimato su RENTRI.',
        ],
        'entries_count' => ['label' => 'Scritture'],
        'is_active' => ['label' => 'Attivo'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuovo registro'],
        'view' => ['label' => 'Dettaglio'],
        'edit' => ['label' => 'Modifica'],
    ],
];
