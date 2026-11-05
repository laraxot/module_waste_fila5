<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Sites',
        'group' => 'Waste',
        'icon' => 'heroicon-o-map-pin',
        'sort' => 30,
    ],
    'label' => 'Site',
    'plural_label' => 'Sites',
    'plural' => ['label' => 'Sites'],
    'fields' => [
        'operator_id' => ['label' => 'Operator'],
        'operator' => [
            'business_name' => ['label' => 'Operator'],
        ],
        'name' => ['label' => 'Name'],
        'rentri_site_number' => [
            'label' => 'RENTRI site registration number',
            'helper_text' => 'num_iscr_sito assigned by RENTRI to the site.',
        ],
        'is_plant' => [
            'label' => 'Managed plant',
            'helper_text' => 'Site of the plant managed with this software (holder of authorizations and register).',
        ],
        'is_active' => ['label' => 'Active'],
        'address' => [
            'label' => 'Address',
            'full_address' => ['label' => 'Address'],
        ],
    ],
    'sections' => [
        'address' => ['label' => 'Address', 'heading' => 'Address'],
    ],
    'actions' => [
        'create' => ['label' => 'New site'],
        'view' => ['label' => 'View'],
        'edit' => ['label' => 'Edit'],
    ],
];
