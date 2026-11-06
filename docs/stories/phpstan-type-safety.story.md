---
type: story
tags: [waste, phpstan, csv, type-safety]
slug: phpstan-type-safety
title: "Tipi espliciti per CSV, Safe functions e test del modulo Waste"
status: review
phase: REVIEW
owner: Waste
depends_on: []
related: []
---

# Tipi espliciti per CSV, Safe functions e test del modulo Waste

## Story

Come manutentore del modulo Waste voglio che l'import EER, i fixture e i test espongano contratti tipizzati
così che gli errori di I/O, le forme degli array e i dati delle enum siano verificabili senza soppressioni PHPStan.

## Soluzione

- Le funzioni filesystem, CSV, regex e encoding usano le varianti `Safe\` e gestiscono esplicitamente i risultati
  che possono essere array o stringhe.
- Le righe CSV sono normalizzate in una lista indicizzata di stringhe prima di arrivare alla logica di dominio.
- `EnumListCast` dichiara il `CreationContext` parametrizzato e i fixture FIR dichiarano la lista di enum HP.
- I modelli e i test non usano conversioni o variabili inutilizzate per nascondere dati non tipizzati.
- Il fixture di migrazione verifica che l'istanza esponga realmente `up()` prima di invocarla.

## Acceptance criteria

1. `./vendor/bin/phpstan analyse Modules/Waste` termina con zero errori.
2. Tutti i file PHP del modulo superano `php -l`.
3. Non vengono introdotti ignore, baseline o `mixed` come scorciatoia.
4. I test restano eseguibili quando è disponibile una connessione database di test.

## Verifica corrente

- PHPStan mirato: superato, 0 errori.
- Lint PHP mirato: superato.
- Pest mirato: non verificato per bootstrap; l'ambiente corrente tenta MySQL senza credenziali (`SQLSTATE[HY000] [1045]`).
