<?php

declare(strict_types=1);

return [
    'label' => 'Codice autorizzato',
    'plural_label' => 'Codici autorizzati',
    'plural' => ['label' => 'Codici autorizzati'],
    'fields' => [
        'ewc_code_id' => ['label' => 'Codice EER'],
        'ewcCode' => [
            'display_code' => ['label' => 'Codice EER'],
            'description' => ['label' => 'Descrizione'],
        ],
        'operations' => [
            'label' => 'Operazioni autorizzate',
            'helper_text' => 'Operazioni di recupero (R) e smaltimento (D) ammesse per il codice.',
        ],
        'max_storage_kg' => [
            'label' => 'Stoccaggio massimo istantaneo',
            'helper_text' => 'Quantità massima presente in impianto in ogni momento (kg). Vuoto = nessun limite specifico.',
        ],
        'max_annual_kg' => [
            'label' => 'Quantità massima annua',
            'helper_text' => 'Quantità massima conferibile nell\'anno solare (kg). Vuoto = nessun limite specifico.',
        ],
        'notes' => ['label' => 'Note'],
    ],
    'actions' => [
        'create' => ['label' => 'Aggiungi codice'],
        'edit' => ['label' => 'Modifica'],
        'delete' => ['label' => 'Rimuovi'],
    ],
];
