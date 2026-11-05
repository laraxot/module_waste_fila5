<?php

declare(strict_types=1);

/*
 * Operazioni di recupero (Allegato C) e di smaltimento (Allegato B) alla Parte IV del D.Lgs. 152/2006.
 * `label` sintetica per elenchi e badge, `description` con il testo dell'allegato.
 */
return [
    'values' => [
        'R1' => ['label' => 'R1 – Recupero energetico', 'description' => 'Utilizzazione principalmente come combustibile o come altro mezzo per produrre energia.', 'color' => 'success', 'icon' => 'heroicon-o-bolt'],
        'R2' => ['label' => 'R2 – Rigenerazione di solventi', 'description' => 'Rigenerazione/recupero di solventi.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R3' => ['label' => 'R3 – Riciclo di sostanze organiche', 'description' => 'Riciclaggio/recupero delle sostanze organiche non utilizzate come solventi (comprese le operazioni di compostaggio e altre trasformazioni biologiche).', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R4' => ['label' => 'R4 – Riciclo di metalli', 'description' => 'Riciclaggio/recupero dei metalli e dei composti metallici.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R5' => ['label' => 'R5 – Riciclo di sostanze inorganiche', 'description' => 'Riciclaggio/recupero di altre sostanze inorganiche.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R6' => ['label' => 'R6 – Rigenerazione di acidi o basi', 'description' => 'Rigenerazione degli acidi o delle basi.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R7' => ['label' => 'R7 – Recupero di prodotti antinquinamento', 'description' => 'Recupero dei prodotti che servono a ridurre l\'inquinamento.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R8' => ['label' => 'R8 – Recupero da catalizzatori', 'description' => 'Recupero dei prodotti provenienti dai catalizzatori.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R9' => ['label' => 'R9 – Rigenerazione di oli', 'description' => 'Rigenerazione o altri reimpieghi degli oli.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R10' => ['label' => 'R10 – Spandimento sul suolo', 'description' => 'Trattamento in ambiente terrestre a beneficio dell\'agricoltura o dell\'ecologia.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R11' => ['label' => 'R11 – Utilizzo di rifiuti da R1-R10', 'description' => 'Utilizzazione di rifiuti ottenuti da una delle operazioni indicate da R1 a R10.', 'color' => 'success', 'icon' => 'heroicon-o-arrow-path'],
        'R12' => ['label' => 'R12 – Scambio/trattamento preliminare per R1-R11', 'description' => 'Scambio di rifiuti per sottoporli a una delle operazioni indicate da R1 a R11.', 'color' => 'info', 'icon' => 'heroicon-o-wrench'],
        'R13' => ['label' => 'R13 – Messa in riserva', 'description' => 'Messa in riserva di rifiuti per sottoporli a una delle operazioni indicate nei punti da R1 a R12 (escluso il deposito temporaneo, prima della raccolta, nel luogo in cui sono prodotti).', 'color' => 'info', 'icon' => 'heroicon-o-archive-box'],
        'D1' => ['label' => 'D1 – Deposito sul o nel suolo', 'description' => 'Deposito sul o nel suolo (ad esempio discarica).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D2' => ['label' => 'D2 – Trattamento in ambiente terrestre', 'description' => 'Trattamento in ambiente terrestre (ad esempio biodegradazione di rifiuti liquidi o fanghi nei suoli).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D3' => ['label' => 'D3 – Iniezioni in profondità', 'description' => 'Iniezioni in profondità (ad esempio iniezioni dei rifiuti pompabili in pozzi, in cupole saline o faglie geologiche naturali).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D4' => ['label' => 'D4 – Lagunaggio', 'description' => 'Lagunaggio (ad esempio scarico di rifiuti liquidi o di fanghi in pozzi, stagni o lagune, ecc.).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D5' => ['label' => 'D5 – Discarica specialmente allestita', 'description' => 'Messa in discarica specialmente allestita (ad esempio sistematizzazione in alveoli stagni, separati, ricoperti o isolati gli uni dagli altri e dall\'ambiente).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D6' => ['label' => 'D6 – Scarico nell\'ambiente idrico', 'description' => 'Scarico dei rifiuti solidi nell\'ambiente idrico eccetto l\'immersione.', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D7' => ['label' => 'D7 – Immersione', 'description' => 'Immersione, compreso il seppellimento nel sottosuolo marino.', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D8' => ['label' => 'D8 – Trattamento biologico', 'description' => 'Trattamento biologico non specificato altrove nel presente allegato, che dia origine a composti o a miscugli che vengono eliminati secondo uno dei procedimenti elencati nei punti da D1 a D12.', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
        'D9' => ['label' => 'D9 – Trattamento chimico-fisico', 'description' => 'Trattamento fisico-chimico non specificato altrove nel presente allegato, che dia origine a composti o a miscugli eliminati secondo uno dei procedimenti elencati nei punti da D1 a D12 (ad esempio evaporazione, essiccazione, calcinazione, ecc.).', 'color' => 'warning', 'icon' => 'heroicon-o-beaker'],
        'D10' => ['label' => 'D10 – Incenerimento a terra', 'description' => 'Incenerimento a terra.', 'color' => 'danger', 'icon' => 'heroicon-o-fire'],
        'D11' => ['label' => 'D11 – Incenerimento in mare', 'description' => 'Incenerimento in mare (operazione vietata dalla normativa UE e dalle convenzioni internazionali).', 'color' => 'danger', 'icon' => 'heroicon-o-fire'],
        'D12' => ['label' => 'D12 – Deposito permanente', 'description' => 'Deposito permanente (ad esempio sistemazione di contenitori in una miniera).', 'color' => 'danger', 'icon' => 'heroicon-o-trash'],
        'D13' => ['label' => 'D13 – Raggruppamento preliminare', 'description' => 'Raggruppamento preliminare prima di una delle operazioni di cui ai punti da D1 a D12.', 'color' => 'info', 'icon' => 'heroicon-o-square-3-stack-3d'],
        'D14' => ['label' => 'D14 – Ricondizionamento preliminare', 'description' => 'Ricondizionamento preliminare prima di una delle operazioni di cui ai punti da D1 a D13.', 'color' => 'info', 'icon' => 'heroicon-o-cube'],
        'D15' => ['label' => 'D15 – Deposito preliminare', 'description' => 'Deposito preliminare prima di una delle operazioni di cui ai punti da D1 a D14 (escluso il deposito temporaneo, prima della raccolta, nel luogo in cui sono prodotti).', 'color' => 'info', 'icon' => 'heroicon-o-archive-box'],
    ],
];
