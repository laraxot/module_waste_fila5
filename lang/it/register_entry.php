<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Scritture del registro',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-clipboard-document-list',
        'sort' => 70,
    ],
    'label' => 'Scrittura',
    'plural_label' => 'Scritture del registro',
    'plural' => ['label' => 'Scritture del registro'],
    'fields' => [
        'register_id' => ['label' => 'Registro'],
        'register' => ['name' => ['label' => 'Registro']],
        'year' => ['label' => 'Anno'],
        'number' => ['label' => 'Numero'],
        'type' => ['label' => 'Tipo'],
        'cause' => ['label' => 'Causale'],
        'recorded_at' => ['label' => 'Data di annotazione'],
        'operation_at' => [
            'label' => 'Data dell\'operazione',
            'helper_text' => 'Data della movimentazione (non può essere futura).',
        ],
        'ewc_code_id' => ['label' => 'Codice EER'],
        'ewc_description' => ['label' => 'Descrizione EER'],
        'ewcCode' => [
            'display_code' => ['label' => 'Codice EER'],
            'description' => ['label' => 'Descrizione EER'],
        ],
        'physical_state' => ['label' => 'Stato fisico'],
        'hazard_properties' => [
            'label' => 'Caratteristiche di pericolo',
            'helper_text' => 'Obbligatorie per i codici pericolosi (*).',
        ],
        'quantity_kg' => ['label' => 'Quantità (kg)'],
        'operation' => [
            'label' => 'Operazione R/D',
            'helper_text' => 'Obbligatoria per consegna a terzi e avvio a trattamento interno.',
        ],
        'fir_id' => ['label' => 'Formulario'],
        'fir' => ['number' => ['label' => 'Formulario']],
        'counterpart_operator_id' => [
            'label' => 'Controparte',
            'helper_text' => 'Produttore (per i carichi da terzi) o destinatario (per le consegne a terzi).',
        ],
        'counterpart' => ['business_name' => ['label' => 'Controparte']],
        'counterpart_fiscal_code' => ['label' => 'Codice fiscale controparte'],
        'load_entry_ids' => [
            'label' => 'Carichi di riferimento',
            'helper_text' => 'Scritture di carico dello stesso codice EER da cui proviene il rifiuto scaricato.',
        ],
        'reference_type' => ['label' => 'Origine'],
        'rectifies_entry_id' => ['label' => 'Rettifica la scrittura'],
        'rectifiedEntry' => ['number' => ['label' => 'Rettifica la scrittura']],
        'rectification_reason' => ['label' => 'Motivo della rettifica'],
        'reason' => [
            'label' => 'Motivo della rettifica',
            'helper_text' => 'La scrittura originale resta nel registro; la rettifica è una nuova scrittura con il proprio numero.',
        ],
        'notes' => ['label' => 'Annotazioni'],
        'transmitted_at' => ['label' => 'Trasmessa a RENTRI il'],
        'rentri_transmission_ref' => ['label' => 'Riferimento trasmissione RENTRI'],
    ],
    'sections' => [
        'registration' => ['label' => 'Annotazione', 'heading' => 'Annotazione'],
        'waste' => ['label' => 'Rifiuto', 'heading' => 'Rifiuto'],
        'references' => ['label' => 'Riferimenti', 'heading' => 'Riferimenti'],
        'transmission' => ['label' => 'Trasmissione RENTRI', 'heading' => 'Trasmissione RENTRI'],
    ],
    'actions' => [
        'view' => ['label' => 'Dettaglio'],
        'rectify' => [
            'label' => 'Rettifica',
            'tooltip' => 'Correggi con una nuova scrittura di rettifica',
            'modal_heading' => 'Rettifica della scrittura',
            'modal_description' => 'Inserire i dati corretti e la motivazione. La scrittura originale non viene modificata.',
            'success' => 'Rettifica annotata con il numero :number',
        ],
        'export_xls' => [
            'label' => 'Esporta',
            'tooltip' => 'Esporta le scritture filtrate in Excel',
        ],
    ],
];
