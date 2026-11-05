<?php

declare(strict_types=1);

return [
    'label' => 'Authorized code',
    'plural_label' => 'Authorized codes',
    'plural' => ['label' => 'Authorized codes'],
    'fields' => [
        'ewc_code_id' => ['label' => 'EWC code'],
        'ewcCode' => [
            'display_code' => ['label' => 'EWC code'],
            'description' => ['label' => 'Description'],
        ],
        'operations' => [
            'label' => 'Authorized operations',
            'helper_text' => 'Recovery (R) and disposal (D) operations allowed for the code.',
        ],
        'max_storage_kg' => [
            'label' => 'Maximum instantaneous storage',
            'helper_text' => 'Maximum quantity on site at any time (kg). Empty = no specific limit.',
        ],
        'max_annual_kg' => [
            'label' => 'Maximum annual quantity',
            'helper_text' => 'Maximum quantity accepted in the calendar year (kg). Empty = no specific limit.',
        ],
        'notes' => ['label' => 'Notes'],
    ],
    'actions' => [
        'create' => ['label' => 'Add code'],
        'edit' => ['label' => 'Edit'],
        'delete' => ['label' => 'Remove'],
    ],
];
