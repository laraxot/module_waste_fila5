<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Unità locali',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-map-pin',
        'sort' => 30,
    ],
    'label' => 'Unità locale',
    'plural_label' => 'Unità locali',
    'plural' => ['label' => 'Unità locali'],
    'fields' => [
        'operator_id' => ['label' => 'Soggetto'],
        'operator' => [
            'business_name' => ['label' => 'Soggetto'],
        ],
        'name' => ['label' => 'Denominazione'],
        'rentri_site_number' => [
            'label' => 'Numero iscrizione unità locale RENTRI',
            'helper_text' => 'num_iscr_sito assegnato da RENTRI all\'unità locale.',
        ],
        'is_plant' => [
            'label' => 'Impianto gestito',
            'helper_text' => 'Unità locale dell\'impianto gestito con questo software (titolare di autorizzazioni e registro).',
        ],
        'is_active' => ['label' => 'Attiva'],
        'address' => [
            'label' => 'Indirizzo',
            'full_address' => ['label' => 'Indirizzo'],
        ],
    ],
    'sections' => [
        'address' => ['label' => 'Indirizzo', 'heading' => 'Indirizzo'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuova unità locale'],
        'view' => ['label' => 'Dettaglio'],
        'edit' => ['label' => 'Modifica'],
    ],
];
