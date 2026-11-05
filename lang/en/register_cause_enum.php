<?php

declare(strict_types=1);

/*
 * Register reason codes. The matching RENTRI code is mapped by the Rentri module.
 */
return [
    'values' => [
        'new_production' => ['label' => 'New production', 'description' => 'Waste produced by the activity (load).', 'color' => 'primary', 'icon' => 'heroicon-o-sparkles'],
        'from_third_parties' => ['label' => 'Received from third parties', 'description' => 'Waste received from another operator (load).', 'color' => 'success', 'icon' => 'heroicon-o-arrow-down-tray'],
        'to_third_parties' => ['label' => 'Delivered to third parties', 'description' => 'Waste delivered to another operator for recovery or disposal (unload).', 'color' => 'warning', 'icon' => 'heroicon-o-arrow-up-tray'],
        'internal_treatment' => ['label' => 'Sent to internal treatment', 'description' => 'Waste sent to treatment within the plant (unload).', 'color' => 'info', 'icon' => 'heroicon-o-wrench'],
        'rejected_return' => ['label' => 'Rejected', 'description' => 'Waste rejected by the recipient (load of the return or unload of the rejected share).', 'color' => 'danger', 'icon' => 'heroicon-o-x-circle'],
        'adjustment' => ['label' => 'Stock adjustment', 'description' => 'Alignment of the recorded stock to the actual stock (load or unload).', 'color' => 'gray', 'icon' => 'heroicon-o-scale'],
    ],
];
