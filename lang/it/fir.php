<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Formulari (FIR)',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-document-text',
        'sort' => 50,
    ],
    'label' => 'Formulario',
    'plural_label' => 'Formulari (FIR)',
    'plural' => ['label' => 'Formulari (FIR)'],
    'fields' => [
        'number' => ['label' => 'Numero formulario'],
        'is_digital' => [
            'label' => 'FIR digitale',
            'helper_text' => 'Formulario digitale (xFIR) gestito tramite RENTRI.',
        ],
        'status' => [
            'label' => 'Stato',
            'helper_text' => 'Per un formulario cartaceo già in viaggio scegliere "In trasporto".',
        ],
        'producer_operator_id' => ['label' => 'Produttore / detentore'],
        'producer_site_id' => ['label' => 'Unità locale del produttore'],
        'recipient_operator_id' => ['label' => 'Destinatario'],
        'recipient_site_id' => ['label' => 'Unità locale di destinazione'],
        'transporter_operator_id' => ['label' => 'Trasportatore'],
        'intermediary_operator_id' => ['label' => 'Intermediario / commerciante'],
        'producer' => ['business_name' => ['label' => 'Produttore / detentore']],
        'producerSite' => ['name' => ['label' => 'Unità locale del produttore']],
        'recipient' => ['business_name' => ['label' => 'Destinatario']],
        'recipientSite' => ['name' => ['label' => 'Unità locale di destinazione']],
        'transporter' => ['business_name' => ['label' => 'Trasportatore']],
        'intermediary' => ['business_name' => ['label' => 'Intermediario / commerciante']],
        'ewc_code_id' => ['label' => 'Codice EER'],
        'ewcCode' => [
            'display_code' => ['label' => 'Codice EER'],
            'description' => ['label' => 'Descrizione EER'],
        ],
        'waste_description' => ['label' => 'Descrizione del rifiuto'],
        'physical_state' => ['label' => 'Stato fisico'],
        'hazard_properties' => [
            'label' => 'Caratteristiche di pericolo',
            'helper_text' => 'Obbligatorie per i codici pericolosi (*).',
        ],
        'operation' => ['label' => 'Operazione di destinazione'],
        'quantity' => ['label' => 'Quantità'],
        'quantity_unit' => ['label' => 'Unità di misura'],
        'is_quantity_estimated' => ['label' => 'Peso da verificarsi a destino'],
        'adr_un_number' => ['label' => 'Numero ONU (ADR)'],
        'vehicle_plate' => ['label' => 'Targa automezzo'],
        'trailer_plate' => ['label' => 'Targa rimorchio'],
        'driver_name' => ['label' => 'Conducente'],
        'transport_started_at' => ['label' => 'Inizio trasporto'],
        'arrived_at' => ['label' => 'Arrivo a destino'],
        'acceptance' => ['label' => 'Esito accettazione'],
        'accepted_quantity_kg' => ['label' => 'Quantità accettata (kg)'],
        'accepted_kg' => [
            'label' => 'Quantità accettata (kg)',
            'helper_text' => 'Peso verificato a destino. Zero se respinto.',
        ],
        'rejection_reason' => ['label' => 'Motivo del respingimento'],
        'reason' => [
            'label' => 'Motivazione',
            'helper_text' => 'Obbligatoria per accettazione parziale e respingimento.',
        ],
        'notes' => ['label' => 'Annotazioni'],
    ],
    'sections' => [
        'identification' => ['label' => 'Identificazione', 'heading' => 'Identificazione'],
        'subjects' => ['label' => 'Soggetti', 'heading' => 'Soggetti'],
        'waste' => ['label' => 'Rifiuto', 'heading' => 'Rifiuto'],
        'transport' => ['label' => 'Trasporto', 'heading' => 'Trasporto'],
        'acceptance_section' => ['label' => 'Accettazione a destino', 'heading' => 'Accettazione a destino'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuovo formulario'],
        'view' => ['label' => 'Dettaglio'],
        'edit' => ['label' => 'Modifica'],
        'change_status' => [
            'label' => 'Cambia stato',
            'modal_heading' => 'Cambia lo stato del formulario',
        ],
        'record_acceptance' => [
            'label' => 'Registra accettazione',
            'modal_heading' => 'Accettazione a destino',
            'modal_description' => 'Indicare il peso verificato a destino e l\'esito.',
        ],
        'done' => [
            'success' => 'Formulario aggiornato',
        ],
    ],
];
