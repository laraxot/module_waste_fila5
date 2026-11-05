<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Soggetti',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-building-office-2',
        'sort' => 20,
    ],
    'label' => 'Soggetto',
    'plural_label' => 'Soggetti',
    'plural' => ['label' => 'Soggetti'],
    'fields' => [
        'business_name' => ['label' => 'Ragione sociale'],
        'fiscal_code' => ['label' => 'Codice fiscale'],
        'vat_number' => ['label' => 'Partita IVA'],
        'roles' => [
            'label' => 'Ruoli',
            'helper_text' => 'Ruoli svolti nella filiera: un soggetto può averne più d\'uno.',
        ],
        'role' => ['label' => 'Ruolo'],
        'rentri_registration_number' => ['label' => 'Numero iscrizione RENTRI'],
        'albo_registration_number' => ['label' => 'Iscrizione Albo Gestori Ambientali'],
        'email' => ['label' => 'Email'],
        'pec' => ['label' => 'PEC'],
        'phone' => ['label' => 'Telefono'],
        'sites_count' => ['label' => 'Unità locali'],
        'is_active' => ['label' => 'Attivo'],
        'tenant_id' => ['label' => 'Cliente (area clienti)', 'helper_text' => 'Gli utenti di questo cliente vedono cantieri, FIR e conferimenti del soggetto'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuovo soggetto'],
        'view' => ['label' => 'Dettaglio'],
        'edit' => ['label' => 'Modifica'],
    ],
];
