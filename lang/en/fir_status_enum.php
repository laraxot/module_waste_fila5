<?php

declare(strict_types=1);

return [
    'values' => [
        'draft' => ['label' => 'Draft', 'color' => 'gray', 'icon' => 'heroicon-o-pencil-square'],
        'issued' => ['label' => 'Issued', 'color' => 'info', 'icon' => 'heroicon-o-document-text'],
        'in_transit' => ['label' => 'In transit', 'color' => 'warning', 'icon' => 'heroicon-o-truck'],
        'delivered' => ['label' => 'Delivered', 'color' => 'primary', 'icon' => 'heroicon-o-inbox-arrow-down'],
        'accepted' => ['label' => 'Accepted', 'color' => 'success', 'icon' => 'heroicon-o-check-circle'],
        'partially_accepted' => ['label' => 'Partially accepted', 'color' => 'warning', 'icon' => 'heroicon-o-check-circle'],
        'rejected' => ['label' => 'Rejected', 'color' => 'danger', 'icon' => 'heroicon-o-x-circle'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'gray', 'icon' => 'heroicon-o-x-circle'],
    ],
];
