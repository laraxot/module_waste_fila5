<?php

declare(strict_types=1);

return [
    'values' => [
        'solid_powdery' => ['label' => 'Powdery solid', 'description' => 'Powder or fine granules, liable to dispersion.', 'color' => 'gray', 'icon' => 'heroicon-o-cloud'],
        'solid_non_powdery' => ['label' => 'Non-powdery solid', 'description' => 'Solid in pieces, not dispersible as dust.', 'color' => 'gray', 'icon' => 'heroicon-o-cube'],
        'sludge' => ['label' => 'Shovelable sludge', 'description' => 'Sludge that can be handled with a shovel.', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
        'liquid' => ['label' => 'Liquid', 'description' => 'Pumpable liquid.', 'color' => 'info', 'icon' => 'heroicon-o-beaker'],
        'viscous' => ['label' => 'Viscous / syrupy', 'description' => 'Viscous material, neither shovelable nor pumpable as a liquid.', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
    ],
];
