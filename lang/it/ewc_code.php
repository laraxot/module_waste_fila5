<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Codici EER',
        'group' => 'Rifiuti',
        'icon' => 'heroicon-o-tag',
        'sort' => 10,
    ],
    'label' => 'Codice EER',
    'plural_label' => 'Codici EER',
    'plural' => ['label' => 'Codici EER'],
    'fields' => [
        'code' => [
            'label' => 'Codice EER',
            'placeholder' => '19 12 04',
            'helper_text' => 'Sei cifre, con o senza spazi; l\'asterisco dei codici pericolosi si indica con "Pericoloso".',
        ],
        'display_code' => ['label' => 'Codice EER'],
        'description' => ['label' => 'Descrizione'],
        'chapter' => ['label' => 'Capitolo'],
        'sub_chapter' => ['label' => 'Sottocapitolo'],
        'is_hazardous' => [
            'label' => 'Pericoloso (*)',
            'helper_text' => 'Voce contrassegnata da asterisco nell\'elenco europeo dei rifiuti.',
        ],
        'is_mirror' => [
            'label' => 'Voce a specchio',
            'helper_text' => 'Voce che ha una corrispondente pericolosa/non pericolosa: la classificazione richiede la verifica delle caratteristiche di pericolo.',
        ],
        'is_active' => ['label' => 'Attivo'],
        'file' => [
            'label' => 'File CSV',
            'helper_text' => 'Separatore ";" e colonne code;description;is_hazardous;is_mirror. Intestazione facoltativa.',
        ],
    ],
    'actions' => [
        'create' => ['label' => 'Nuovo codice EER'],
        'edit' => ['label' => 'Modifica'],
        'import_csv' => [
            'label' => 'Importa CSV',
            'tooltip' => 'Importa o aggiorna il catalogo EER da file CSV',
            'modal_heading' => 'Importa codici EER',
            'modal_description' => 'I codici esistenti vengono aggiornati, quelli nuovi aggiunti. In caso di errore nessuna riga viene salvata.',
            'success' => ':count codici EER importati o aggiornati',
            'error' => 'Importazione non riuscita',
        ],
    ],
    'validation' => [
        'duplicate_code' => 'Il codice EER :code esiste già.',
    ],
];
