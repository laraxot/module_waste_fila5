<?php

declare(strict_types=1);

/*
 * Hazardous properties — Annex III to Directive 2008/98/EC (Regulation (EU) No 1357/2014).
 */
return [
    'values' => [
        'HP1' => [
            'label' => 'HP1 – Explosive',
            'description' => 'Waste which is capable by chemical reaction of producing gas at such a temperature and pressure and at such a speed as to cause damage to the surroundings.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP2' => [
            'label' => 'HP2 – Oxidising',
            'description' => 'Waste which may, generally by providing oxygen, cause or contribute to the combustion of other materials.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP3' => [
            'label' => 'HP3 – Flammable',
            'description' => 'Flammable liquid, solid or gaseous waste, or waste which in contact with water or air evolves flammable gases.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP4' => [
            'label' => 'HP4 – Irritant',
            'description' => 'Skin irritation and eye damage: waste which on application can cause skin irritation or damage to the eye.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP5' => [
            'label' => 'HP5 – Specific Target Organ Toxicity (STOT) / Aspiration Toxicity',
            'description' => 'Waste which can cause specific target organ toxicity either from a single or repeated exposure, or which cause acute toxic effects following aspiration.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP6' => [
            'label' => 'HP6 – Acute Toxicity',
            'description' => 'Waste which can cause acute toxic effects following oral or dermal administration, or inhalation exposure.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP7' => [
            'label' => 'HP7 – Carcinogenic',
            'description' => 'Waste which induces cancer or increases its incidence.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP8' => [
            'label' => 'HP8 – Corrosive',
            'description' => 'Waste which on application can cause skin corrosion.',
            'color' => 'danger',
            'icon' => 'heroicon-o-beaker',
        ],
        'HP9' => [
            'label' => 'HP9 – Infectious',
            'description' => 'Waste containing viable micro-organisms or their toxins which are known or reliably believed to cause disease in man or other living organisms.',
            'color' => 'danger',
            'icon' => 'heroicon-o-bug-ant',
        ],
        'HP10' => [
            'label' => 'HP10 – Toxic for reproduction',
            'description' => 'Waste which has adverse effects on sexual function and fertility in adult males and females, as well as developmental toxicity in the offspring.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP11' => [
            'label' => 'HP11 – Mutagenic',
            'description' => 'Waste which may cause a mutation, that is a permanent change in the amount or structure of the genetic material in a cell.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP12' => [
            'label' => 'HP12 – Release of an acute toxic gas',
            'description' => 'Waste which releases acute toxic gases (Acute Tox. 1, 2 or 3) in contact with water or an acid.',
            'color' => 'danger',
            'icon' => 'heroicon-o-cloud',
        ],
        'HP13' => [
            'label' => 'HP13 – Sensitising',
            'description' => 'Waste which contains one or more substances known to cause sensitising effects to the skin or the respiratory organs.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP14' => [
            'label' => 'HP14 – Ecotoxic',
            'description' => 'Waste which presents or may present immediate or delayed risks for one or more sectors of the environment.',
            'color' => 'warning',
            'icon' => 'heroicon-o-globe-europe-africa',
        ],
        'HP15' => [
            'label' => 'HP15 – Waste capable of exhibiting a hazardous property not directly displayed',
            'description' => 'Waste capable of exhibiting a hazardous property listed above not directly displayed by the original waste.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
    ],
];
