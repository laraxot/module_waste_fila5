<?php

declare(strict_types=1);

/*
 * Caratteristiche di pericolo — Allegato I alla Parte IV del D.Lgs. 152/2006 (Reg. UE 1357/2014).
 * Chiavi lette da EnumTrait: waste::hazard_property_enum.values.<valore>.<attributo>.
 */
return [
    'values' => [
        'HP1' => [
            'label' => 'HP1 – Esplosivo',
            'description' => 'Rifiuto che può, per reazione chimica, sviluppare gas a una temperatura, una pressione e una velocità tali da causare danni nell\'area circostante.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP2' => [
            'label' => 'HP2 – Comburente',
            'description' => 'Rifiuto capace, in genere per apporto di ossigeno, di provocare o favorire la combustione di altre materie.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP3' => [
            'label' => 'HP3 – Infiammabile',
            'description' => 'Rifiuto liquido, solido o gassoso infiammabile, o che a contatto con l\'acqua o con l\'aria sviluppa gas infiammabili.',
            'color' => 'danger',
            'icon' => 'heroicon-o-fire',
        ],
        'HP4' => [
            'label' => 'HP4 – Irritante',
            'description' => 'Irritazione cutanea e lesioni oculari: rifiuto la cui applicazione può provocare irritazione cutanea o lesioni oculari.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP5' => [
            'label' => 'HP5 – Tossicità specifica per organi bersaglio (STOT) / tossicità in caso di aspirazione',
            'description' => 'Rifiuto che può causare tossicità specifica per organi bersaglio con un\'esposizione singola o ripetuta, o effetti tossici acuti in seguito all\'aspirazione.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP6' => [
            'label' => 'HP6 – Tossicità acuta',
            'description' => 'Rifiuto che può provocare effetti tossici acuti in seguito alla somministrazione per via orale o cutanea, o all\'esposizione per inalazione.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP7' => [
            'label' => 'HP7 – Cancerogeno',
            'description' => 'Rifiuto che causa il cancro o ne aumenta l\'incidenza.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP8' => [
            'label' => 'HP8 – Corrosivo',
            'description' => 'Rifiuto la cui applicazione può provocare corrosione cutanea.',
            'color' => 'danger',
            'icon' => 'heroicon-o-beaker',
        ],
        'HP9' => [
            'label' => 'HP9 – Infettivo',
            'description' => 'Rifiuto contenente microrganismi vitali o loro tossine che sono cause note, o a ragion veduta ritenuti tali, di malattie nell\'uomo o in altri organismi viventi.',
            'color' => 'danger',
            'icon' => 'heroicon-o-bug-ant',
        ],
        'HP10' => [
            'label' => 'HP10 – Tossico per la riproduzione',
            'description' => 'Rifiuto che ha effetti nocivi sulla funzione sessuale e sulla fertilità degli uomini e delle donne adulti, nonché sullo sviluppo della progenie.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP11' => [
            'label' => 'HP11 – Mutageno',
            'description' => 'Rifiuto che può causare una mutazione, ossia una variazione permanente della quantità o della struttura del materiale genetico di una cellula.',
            'color' => 'danger',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP12' => [
            'label' => 'HP12 – Liberazione di gas a tossicità acuta',
            'description' => 'Rifiuto che libera gas a tossicità acuta (Acute Tox. 1, 2 o 3) a contatto con l\'acqua o con un acido.',
            'color' => 'danger',
            'icon' => 'heroicon-o-cloud',
        ],
        'HP13' => [
            'label' => 'HP13 – Sensibilizzante',
            'description' => 'Rifiuto che contiene una o più sostanze note per essere all\'origine di effetti di sensibilizzazione per la pelle o gli organi respiratori.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
        'HP14' => [
            'label' => 'HP14 – Ecotossico',
            'description' => 'Rifiuto che presenta o può presentare rischi immediati o differiti per uno o più comparti ambientali.',
            'color' => 'warning',
            'icon' => 'heroicon-o-globe-europe-africa',
        ],
        'HP15' => [
            'label' => 'HP15 – Rifiuto che può manifestare una caratteristica di pericolo non posseduta direttamente',
            'description' => 'Rifiuto che non possiede direttamente una delle caratteristiche di pericolo summenzionate ma può manifestarla successivamente.',
            'color' => 'warning',
            'icon' => 'heroicon-o-exclamation-triangle',
        ],
    ],
];
