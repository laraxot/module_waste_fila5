<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Waste registers',
        'group' => 'Waste',
        'icon' => 'heroicon-o-book-open',
        'sort' => 60,
    ],
    'label' => 'Register',
    'plural_label' => 'Waste registers',
    'plural' => ['label' => 'Waste registers'],
    'fields' => [
        'operator_site_id' => ['label' => 'Site'],
        'site' => [
            'name' => ['label' => 'Site'],
        ],
        'name' => ['label' => 'Name'],
        'rentri_identifier' => [
            'label' => 'RENTRI register identifier',
            'helper_text' => 'Identifier of the register validated on RENTRI.',
        ],
        'entries_count' => ['label' => 'Entries'],
        'is_active' => ['label' => 'Active'],
    ],
    'actions' => [
        'create' => ['label' => 'New register'],
        'view' => ['label' => 'View'],
        'edit' => ['label' => 'Edit'],
    ],
];
