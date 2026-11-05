<?php

declare(strict_types=1);

/*
 * Punti di estensione del modulo Waste (letti come `config('waste.*')`).
 * Le regole normative (numerazione del registro, coerenza EER/HP, transizioni FIR) NON sono configurabili:
 * stanno nel codice perché sono invarianti di legge.
 */
return [
    'name' => 'Waste',
    'description' => 'Dominio normativo dei rifiuti: EER, soggetti, autorizzazioni, FIR, registro di carico e scarico',
    'icon' => 'heroicon-o-trash',
    'navigation' => [
        'enabled' => true,
        'sort' => 30,
    ],

    'register' => [
        /*
         * Tentativi della transazione di scrittura sul registro in caso di deadlock / "database is locked"
         * o di collisione sul vincolo unique (register_id, year, number). Dipende dal carico del DB.
         */
        'transaction_attempts' => (int) env('WASTE_REGISTER_TRANSACTION_ATTEMPTS', 5),
    ],

    'authorization_document' => [
        /*
         * Disco medialibrary per gli atti autorizzativi (null = disco di default di medialibrary).
         * Gli atti sono documenti riservati: in produzione usare un disco privato.
         */
        'disk' => env('WASTE_AUTHORIZATION_DOCUMENT_DISK'),
        /*
         * Tipi MIME accettati: PDF e file firmati CAdES (.p7m), formato usuale degli atti della PA.
         */
        'mime_types' => [
            'application/pdf',
            'application/pkcs7-mime',
            'application/x-pkcs7-mime',
        ],
    ],

    'ewc_import' => [
        /*
         * Disco su cui la header action "Importa CSV" salva temporaneamente il file caricato.
         */
        'disk' => 'local',
        'directory' => 'waste/imports',
    ],
];
