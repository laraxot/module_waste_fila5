<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Operators',
        'group' => 'Waste',
        'icon' => 'heroicon-o-building-office-2',
        'sort' => 20,
    ],
    'label' => 'Operator',
    'plural_label' => 'Operators',
    'plural' => ['label' => 'Operators'],
    'fields' => [
        'business_name' => ['label' => 'Business name'],
        'fiscal_code' => ['label' => 'Tax code'],
        'vat_number' => ['label' => 'VAT number'],
        'roles' => [
            'label' => 'Roles',
            'helper_text' => 'Roles in the waste chain: an operator may have more than one.',
        ],
        'role' => ['label' => 'Role'],
        'rentri_registration_number' => ['label' => 'RENTRI registration number'],
        'albo_registration_number' => ['label' => 'National Register of Environmental Managers'],
        'email' => ['label' => 'Email'],
        'pec' => ['label' => 'Certified email (PEC)'],
        'phone' => ['label' => 'Phone'],
        'sites_count' => ['label' => 'Sites'],
        'is_active' => ['label' => 'Active'],
<<<<<<< HEAD
=======
        'tenant_id' => ['label' => 'Customer (customer area)', 'helper_text' => 'Users of this customer see the sites, FIRs and deliveries of this operator'],
>>>>>>> laraxot/dev
    ],
    'actions' => [
        'create' => ['label' => 'New operator'],
        'view' => ['label' => 'View'],
        'edit' => ['label' => 'Edit'],
    ],
];
