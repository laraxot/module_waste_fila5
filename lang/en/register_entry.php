<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Register entries',
        'group' => 'Waste',
        'icon' => 'heroicon-o-clipboard-document-list',
        'sort' => 70,
    ],
    'label' => 'Entry',
    'plural_label' => 'Register entries',
    'plural' => ['label' => 'Register entries'],
    'fields' => [
        'register_id' => ['label' => 'Register'],
        'register' => ['name' => ['label' => 'Register']],
        'year' => ['label' => 'Year'],
        'number' => ['label' => 'Number'],
        'type' => ['label' => 'Type'],
        'cause' => ['label' => 'Reason code'],
        'recorded_at' => ['label' => 'Recorded at'],
        'operation_at' => [
            'label' => 'Operation date',
            'helper_text' => 'Date of the waste movement (cannot be in the future).',
        ],
        'ewc_code_id' => ['label' => 'EWC code'],
        'ewc_description' => ['label' => 'EWC description'],
        'ewcCode' => [
            'display_code' => ['label' => 'EWC code'],
            'description' => ['label' => 'EWC description'],
        ],
        'physical_state' => ['label' => 'Physical state'],
        'hazard_properties' => [
            'label' => 'Hazardous properties',
            'helper_text' => 'Required for hazardous codes (*).',
        ],
        'quantity_kg' => ['label' => 'Quantity (kg)'],
        'operation' => [
            'label' => 'R/D operation',
            'helper_text' => 'Required for delivery to third parties and internal treatment.',
        ],
        'fir_id' => ['label' => 'Waste form'],
        'fir' => ['number' => ['label' => 'Waste form']],
        'counterpart_operator_id' => [
            'label' => 'Counterpart',
            'helper_text' => 'Producer (for loads from third parties) or recipient (for deliveries to third parties).',
        ],
        'counterpart' => ['business_name' => ['label' => 'Counterpart']],
        'counterpart_fiscal_code' => ['label' => 'Counterpart tax code'],
        'load_entry_ids' => [
            'label' => 'Referenced loads',
            'helper_text' => 'Load entries of the same EWC code the unloaded waste comes from.',
        ],
        'reference_type' => ['label' => 'Origin'],
        'rectifies_entry_id' => ['label' => 'Rectifies entry'],
        'rectifiedEntry' => ['number' => ['label' => 'Rectifies entry']],
        'rectification_reason' => ['label' => 'Rectification reason'],
        'reason' => [
            'label' => 'Rectification reason',
            'helper_text' => 'The original entry stays in the register; the rectification is a new entry with its own number.',
        ],
        'notes' => ['label' => 'Notes'],
        'transmitted_at' => ['label' => 'Transmitted to RENTRI at'],
        'rentri_transmission_ref' => ['label' => 'RENTRI transmission reference'],
    ],
    'sections' => [
        'registration' => ['label' => 'Recording', 'heading' => 'Recording'],
        'waste' => ['label' => 'Waste', 'heading' => 'Waste'],
        'references' => ['label' => 'References', 'heading' => 'References'],
        'transmission' => ['label' => 'RENTRI transmission', 'heading' => 'RENTRI transmission'],
    ],
    'actions' => [
        'view' => ['label' => 'View'],
        'rectify' => [
            'label' => 'Rectify',
            'tooltip' => 'Correct with a new rectification entry',
            'modal_heading' => 'Rectify entry',
            'modal_description' => 'Enter the corrected data and the reason. The original entry is not changed.',
            'success' => 'Rectification recorded as number :number',
        ],
        'export_xls' => [
            'label' => 'Export',
            'tooltip' => 'Export the filtered entries to Excel',
        ],
    ],
];
