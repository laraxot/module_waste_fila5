<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Plant authorizations',
        'group' => 'Waste',
        'icon' => 'heroicon-o-shield-check',
        'sort' => 40,
    ],
    'label' => 'Authorization',
    'plural_label' => 'Plant authorizations',
    'plural' => ['label' => 'Plant authorizations'],
    'fields' => [
        'operator_site_id' => ['label' => 'Plant'],
        'site' => [
            'name' => ['label' => 'Plant'],
        ],
        'type' => ['label' => 'Permit type'],
        'number' => ['label' => 'Permit number'],
        'issuing_authority' => ['label' => 'Competent authority'],
        'issued_at' => ['label' => 'Issue date'],
        'expires_at' => [
            'label' => 'Expiry date',
            'helper_text' => 'Empty if the permit does not expire. The date is included in the validity period.',
        ],
        'is_active' => ['label' => 'Active'],
        'notes' => ['label' => 'Notes'],
        'document' => [
            'label' => 'Permit document',
            'helper_text' => 'PDF or digitally signed file (.p7m).',
        ],
        'authorized_wastes_count' => ['label' => 'Authorized codes'],
        'valid_today' => ['label' => 'Valid today'],
    ],
    'actions' => [
        'create' => ['label' => 'New authorization'],
        'view' => ['label' => 'View'],
        'edit' => ['label' => 'Edit'],
    ],
];
