<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'EWC codes',
        'group' => 'Waste',
        'icon' => 'heroicon-o-tag',
        'sort' => 10,
    ],
    'label' => 'EWC code',
    'plural_label' => 'EWC codes',
    'plural' => ['label' => 'EWC codes'],
    'fields' => [
        'code' => [
            'label' => 'EWC code',
            'placeholder' => '19 12 04',
            'helper_text' => 'Six digits, with or without spaces; mark hazardous entries (asterisk) with "Hazardous".',
        ],
        'display_code' => ['label' => 'EWC code'],
        'description' => ['label' => 'Description'],
        'chapter' => ['label' => 'Chapter'],
        'sub_chapter' => ['label' => 'Sub-chapter'],
        'is_hazardous' => [
            'label' => 'Hazardous (*)',
            'helper_text' => 'Entry marked with an asterisk in the European List of Waste.',
        ],
        'is_mirror' => [
            'label' => 'Mirror entry',
            'helper_text' => 'Entry with a hazardous/non-hazardous counterpart: classification requires assessing the hazardous properties.',
        ],
        'is_active' => ['label' => 'Active'],
        'file' => [
            'label' => 'CSV file',
            'helper_text' => 'Separator ";" and columns code;description;is_hazardous;is_mirror. Header row optional.',
        ],
    ],
    'actions' => [
        'create' => ['label' => 'New EWC code'],
        'edit' => ['label' => 'Edit'],
        'import_csv' => [
            'label' => 'Import CSV',
            'tooltip' => 'Import or update the EWC catalogue from a CSV file',
            'modal_heading' => 'Import EWC codes',
            'modal_description' => 'Existing codes are updated, new ones added. On error no row is saved.',
            'success' => ':count EWC codes imported or updated',
            'error' => 'Import failed',
        ],
    ],
    'validation' => [
        'duplicate_code' => 'EWC code :code already exists.',
    ],
];
