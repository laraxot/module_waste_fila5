<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Waste forms (FIR)',
        'group' => 'Waste',
        'icon' => 'heroicon-o-document-text',
        'sort' => 50,
    ],
    'label' => 'Waste form',
    'plural_label' => 'Waste forms (FIR)',
    'plural' => ['label' => 'Waste forms (FIR)'],
    'fields' => [
        'number' => ['label' => 'Form number'],
        'is_digital' => [
            'label' => 'Digital FIR',
            'helper_text' => 'Digital form (xFIR) managed through RENTRI.',
        ],
        'status' => [
            'label' => 'Status',
            'helper_text' => 'For a paper form already travelling choose "In transit".',
        ],
        'producer_operator_id' => ['label' => 'Producer / holder'],
        'producer_site_id' => ['label' => 'Producer site'],
        'recipient_operator_id' => ['label' => 'Recipient'],
        'recipient_site_id' => ['label' => 'Destination site'],
        'transporter_operator_id' => ['label' => 'Carrier'],
        'intermediary_operator_id' => ['label' => 'Broker / dealer'],
        'producer' => ['business_name' => ['label' => 'Producer / holder']],
        'producerSite' => ['name' => ['label' => 'Producer site']],
        'recipient' => ['business_name' => ['label' => 'Recipient']],
        'recipientSite' => ['name' => ['label' => 'Destination site']],
        'transporter' => ['business_name' => ['label' => 'Carrier']],
        'intermediary' => ['business_name' => ['label' => 'Broker / dealer']],
        'ewc_code_id' => ['label' => 'EWC code'],
        'ewcCode' => [
            'display_code' => ['label' => 'EWC code'],
            'description' => ['label' => 'EWC description'],
        ],
        'waste_description' => ['label' => 'Waste description'],
        'physical_state' => ['label' => 'Physical state'],
        'hazard_properties' => [
            'label' => 'Hazardous properties',
            'helper_text' => 'Required for hazardous codes (*).',
        ],
        'operation' => ['label' => 'Destination operation'],
        'quantity' => ['label' => 'Quantity'],
        'quantity_unit' => ['label' => 'Unit'],
        'is_quantity_estimated' => ['label' => 'Weight to be verified at destination'],
        'adr_un_number' => ['label' => 'UN number (ADR)'],
        'vehicle_plate' => ['label' => 'Vehicle plate'],
        'trailer_plate' => ['label' => 'Trailer plate'],
        'driver_name' => ['label' => 'Driver'],
        'transport_started_at' => ['label' => 'Transport start'],
        'arrived_at' => ['label' => 'Arrival at destination'],
        'acceptance' => ['label' => 'Acceptance outcome'],
        'accepted_quantity_kg' => ['label' => 'Accepted quantity (kg)'],
        'accepted_kg' => [
            'label' => 'Accepted quantity (kg)',
            'helper_text' => 'Weight verified at destination. Zero if rejected.',
        ],
        'rejection_reason' => ['label' => 'Rejection reason'],
        'reason' => [
            'label' => 'Reason',
            'helper_text' => 'Required for partial acceptance and rejection.',
        ],
        'notes' => ['label' => 'Notes'],
    ],
    'sections' => [
        'identification' => ['label' => 'Identification', 'heading' => 'Identification'],
        'subjects' => ['label' => 'Parties', 'heading' => 'Parties'],
        'waste' => ['label' => 'Waste', 'heading' => 'Waste'],
        'transport' => ['label' => 'Transport', 'heading' => 'Transport'],
        'acceptance_section' => ['label' => 'Acceptance at destination', 'heading' => 'Acceptance at destination'],
    ],
    'actions' => [
        'create' => ['label' => 'New waste form'],
        'view' => ['label' => 'View'],
        'edit' => ['label' => 'Edit'],
        'change_status' => [
            'label' => 'Change status',
            'modal_heading' => 'Change the form status',
        ],
        'record_acceptance' => [
            'label' => 'Record acceptance',
            'modal_heading' => 'Acceptance at destination',
            'modal_description' => 'Enter the weight verified at destination and the outcome.',
        ],
        'done' => [
            'success' => 'Waste form updated',
        ],
    ],
];
