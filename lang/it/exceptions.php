<?php

declare(strict_types=1);

/*
 * Messaggi delle eccezioni di dominio (Modules\Waste\Exceptions\*), mostrati così come sono all'operatore.
 */
return [
    'invalid_ewc_code' => [
        'malformed' => 'Codice EER non valido: ":code". Atteso un codice di sei cifre, ad esempio 19 12 04.',
        'chapter_out_of_range' => 'Codice EER non valido: ":code". Il capitolo deve essere compreso tra 01 e 20.',
        'not_found' => 'Codice EER #:id inesistente.',
        'inactive' => 'Il codice EER :code non è attivo.',
    ],
    'invalid_quantity' => [
        'not_positive' => 'La quantità deve essere maggiore di zero (indicata: :quantity).',
        'negative' => 'La quantità non può essere negativa (indicata: :quantity).',
        'rejected_must_be_zero' => 'Per un rifiuto respinto la quantità accettata deve essere zero (indicata: :quantity).',
    ],
    'hazard_mismatch' => [
        'missing_for_hazardous' => 'Il codice EER :code è pericoloso: indicare almeno una caratteristica di pericolo (HP).',
        'present_for_non_hazardous' => 'Il codice EER :code non è pericoloso: non si indicano caratteristiche di pericolo (HP).',
    ],
    'invalid_register_entry' => [
        'register_not_found' => 'Registro #:id inesistente.',
        'register_inactive' => 'Il registro ":register" non è attivo.',
        'cause_not_allowed' => 'La causale ":cause" non è ammessa per una scrittura di tipo ":type".',
        'operation_required' => 'Per la causale ":cause" indicare l\'operazione di recupero o smaltimento (R/D).',
        'counterpart_required' => 'Per la causale ":cause" indicare il soggetto controparte.',
        'counterpart_not_found' => 'Soggetto controparte #:id inesistente.',
        'operation_in_future' => 'La data dell\'operazione (:operation_at) non può essere nel futuro.',
        'load_references_on_load' => 'Una scrittura di carico non può riferirsi ad altri carichi.',
        'load_references_missing' => 'Uno scarico deve indicare almeno un carico di riferimento.',
        'load_reference_invalid' => 'La scrittura #:id non è un carico di questo registro.',
        'load_reference_ewc_mismatch' => 'Il carico #:id ha codice EER :actual, diverso da quello dello scarico (:expected).',
        'load_reference_superseded' => 'Il carico #:id è stato rettificato: indicare la scrittura di rettifica.',
        'fir_not_found' => 'Formulario #:id inesistente.',
        'fir_cancelled' => 'Il formulario :fir è annullato.',
        'fir_ewc_mismatch' => 'Il formulario :fir riporta un codice EER diverso da :code.',
        'rectification_register_mismatch' => 'La rettifica deve essere annotata sullo stesso registro della scrittura originale.',
        'rectification_type_mismatch' => 'La rettifica deve avere lo stesso tipo (carico/scarico) della scrittura originale.',
        'rectification_reason_missing' => 'Indicare il motivo della rettifica.',
    ],
    'immutable_fir' => [
        'fields' => 'Il formulario :fir è già emesso: non si possono modificare :fields. Dopo la bozza cambiano solo stato, date di viaggio, accettazione e numero RENTRI.',
        'status' => 'Il formulario :fir non può passare dallo stato :from allo stato :to.',
        'deletion' => 'Il formulario :fir è già emesso e non si può eliminare: va annullato.',
    ],
    'immutable_register_entry' => [
        'transmitted' => 'La scrittura :year/:number è stata trasmessa a RENTRI e non può essere modificata: annotare una rettifica.',
        'numbering' => 'Registro, anno, numero e data di annotazione della scrittura :year/:number non possono essere modificati.',
        'deletion' => 'La scrittura :year/:number non può essere cancellata: annotare una rettifica.',
    ],
    'register_entry_already_rectified' => 'La scrittura :year/:number è già stata rettificata: rettificare la scrittura di rettifica.',
    'invalid_fir_status_transition' => [
        'not_allowed' => 'Il formulario non può passare dallo stato ":from" allo stato ":to".',
        'acceptance_requires_data' => 'Lo stato ":to" si registra con l\'accettazione a destino, indicando la quantità accettata.',
        'invalid_initial_status' => 'Un formulario non può essere creato nello stato ":status".',
        'not_ready_for_transport' => 'Il formulario cartaceo non si può emettere senza :fields: completali con «Modifica» mentre è in bozza.',
        'fields' => [
            'number' => 'numero del formulario',
            'and' => 'e',
            'transporter_operator_id' => 'trasportatore',
        ],
    ],
    'invalid_fir' => [
        'operator_not_found' => 'Soggetto #:id inesistente.',
        'operator_role_mismatch' => 'Il soggetto ":operator" non ha il ruolo richiesto (:role).',
        'site_not_of_operator' => 'L\'unità locale #:id non appartiene a ":operator".',
        'reason_required' => 'Per l\'esito ":acceptance" indicare la motivazione.',
        'duplicate_number' => 'Esiste già un formulario con numero :number.',
    ],
    'ewc_csv_import' => [
        'unreadable' => 'File CSV non leggibile: :path.',
        'invalid_row' => 'Riga :line non valida: :reason',
    ],
];
