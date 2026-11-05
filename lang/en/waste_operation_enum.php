<?php

declare(strict_types=1);

/*
 * Recovery (Annex II) and disposal (Annex I) operations of Directive 2008/98/EC,
 * transposed as Annexes C and B to Part IV of Italian Legislative Decree 152/2006.
 */
return [
    'values' => [
        'R1' => ['label' => 'R1 – Energy recovery', 'description' => 'Use principally as a fuel or other means to generate energy.', 'color' => 'success', 'icon' => 'heroicon-o-bolt'],
        'R2' => ['label' => 'R2 – Solvent reclamation', 'description' => 'Solvent reclamation/regeneration.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R3' => ['label' => 'R3 – Recycling of organic substances', 'description' => 'Recycling/reclamation of organic substances which are not used as solvents (including composting and other biological transformation processes).', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R4' => ['label' => 'R4 – Recycling of metals', 'description' => 'Recycling/reclamation of metals and metal compounds.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R5' => ['label' => 'R5 – Recycling of inorganic materials', 'description' => 'Recycling/reclamation of other inorganic materials.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R6' => ['label' => 'R6 – Regeneration of acids or bases', 'description' => 'Regeneration of acids or bases.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R7' => ['label' => 'R7 – Recovery of pollution abatement components', 'description' => 'Recovery of components used for pollution abatement.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R8' => ['label' => 'R8 – Recovery from catalysts', 'description' => 'Recovery of components from catalysts.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R9' => ['label' => 'R9 – Oil re-refining', 'description' => 'Oil re-refining or other reuses of oil.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R10' => ['label' => 'R10 – Land treatment', 'description' => 'Land treatment resulting in benefit to agriculture or ecological improvement.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R11' => ['label' => 'R11 – Use of waste from R1-R10', 'description' => 'Use of waste obtained from any of the operations numbered R1 to R10.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R12' => ['label' => 'R12 – Exchange/pre-treatment for R1-R11', 'description' => 'Exchange of waste for submission to any of the operations numbered R1 to R11.', 'color' => 'info', 'icon' => 'heroicon-o-wrench'],
        'R13' => ['label' => 'R13 – Storage pending recovery', 'description' => 'Storage of waste pending any of the operations numbered R1 to R12 (excluding temporary storage, pending collection, on the site where the waste is produced).', 'color' => 'info', 'icon' => 'heroicon-o-archive-box'],
        'D1' => ['label' => 'D1 – Deposit into or on to land', 'description' => 'Deposit into or on to land (e.g. landfill).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D2' => ['label' => 'D2 – Land treatment', 'description' => 'Land treatment (e.g. biodegradation of liquid or sludgy discards in soils).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D3' => ['label' => 'D3 – Deep injection', 'description' => 'Deep injection (e.g. injection of pumpable discards into wells, salt domes or naturally occurring repositories).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D4' => ['label' => 'D4 – Surface impoundment', 'description' => 'Surface impoundment (e.g. placement of liquid or sludgy discards into pits, ponds or lagoons, etc.).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D5' => ['label' => 'D5 – Specially engineered landfill', 'description' => 'Specially engineered landfill (e.g. placement into lined discrete cells which are capped and isolated from one another and the environment, etc.).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D6' => ['label' => 'D6 – Release into a water body', 'description' => 'Release into a water body except seas/oceans.', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D7' => ['label' => 'D7 – Release to seas/oceans', 'description' => 'Release to seas/oceans including sea-bed insertion.', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D8' => ['label' => 'D8 – Biological treatment', 'description' => 'Biological treatment not specified elsewhere which results in final compounds or mixtures which are discarded by means of any of the operations numbered D1 to D12.', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
        'D9' => ['label' => 'D9 – Physico-chemical treatment', 'description' => 'Physico-chemical treatment not specified elsewhere which results in final compounds or mixtures which are discarded by means of any of the operations numbered D1 to D12 (e.g. evaporation, drying, calcination, etc.).', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
        'D10' => ['label' => 'D10 – Incineration on land', 'description' => 'Incineration on land.', 'color' => 'danger', 'icon' => 'heroicon-o-fire'],
        'D11' => ['label' => 'D11 – Incineration at sea', 'description' => 'Incineration at sea (prohibited by EU legislation and international conventions).', 'color' => 'danger', 'icon' => 'heroicon-o-fire'],
        'D12' => ['label' => 'D12 – Permanent storage', 'description' => 'Permanent storage (e.g. emplacement of containers in a mine, etc.).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D13' => ['label' => 'D13 – Blending or mixing', 'description' => 'Blending or mixing prior to submission to any of the operations numbered D1 to D12.', 'color' => 'info', 'icon' => 'heroicon-o-square-3-stack-3d'],
        'D14' => ['label' => 'D14 – Repackaging', 'description' => 'Repackaging prior to submission to any of the operations numbered D1 to D13.', 'color' => 'info', 'icon' => 'heroicon-o-cube'],
        'D15' => ['label' => 'D15 – Storage pending disposal', 'description' => 'Storage pending any of the operations numbered D1 to D14 (excluding temporary storage, pending collection, on the site where the waste is produced).', 'color' => 'info', 'icon' => 'heroicon-o-archive-box'],
    ],
];
