---
type: story
tags: [waste, warehouse, processing, migrazioni, audit-database-r15]
slug: legal-tables-migrations
title: "Migrazioni: niente rollback distruttivo sui dati legali, unique e soft delete valutati"
status: review
phase: REVIEW
owner: Waste
depends_on: []
related: []
created: 2026-11-06
updated: 2026-11-06
---

# Migrazioni: niente rollback distruttivo sui dati legali, unique e soft delete valutati

## Story

Come **responsabile dei dati dell'impianto**
voglio che **un `migrate:rollback` non possa cancellare registro, FIR, movimenti e genealogia**
così che **un errore di manutenzione non distrugga dati con valore legale**.

## Perché / evidenze

Audit database R15: `XotBaseMigration::down()` fa `dropIfExists` della tabella del modello; un rollback
cancellava `register_entries`, `stock_movements`, `firs`.

## Soluzione

`down()` che lancia `LogicException` ("Rollback vietato...") nelle migrazioni di:

| Modulo | Tabelle |
|---|---|
| Waste | `firs`, `registers`, `register_entries` |
| Warehouse | `stock_units`, `stock_movements` |
| Processing | `processing_inputs`, `processing_outputs`, `lineage_links` |

Le altre tabelle (anagrafiche, configurazione) mantengono il `down()` di Xot. Le migrazioni di soli indici
(`ensureIndexes`) non hanno `down()`.

## Unique e soft delete: valutazione caso per caso

| Vincolo | Tabella con soft delete | Decisione |
|---|---|---|
| `warehouses.code` | sì | resta: il codice di un magazzino eliminato resta riservato (etichette e QR stampati lo riportano); per riusarlo si ripristina il record. Il form ha `unique(ignoreRecord)`, che conta anche i record eliminati: errore di validazione, non SQL |
| `areas (warehouse_id, code)` | sì | come sopra (form con `unique` per magazzino) |
| `machines.code` (Processing) | sì | come sopra (form con `unique`) |
| `stock_units.code` | sì | voluto: codice progressivo nel QR, mai riusato |
| `firs.number`, `register_entries (register_id, year, number)`, `ewc_codes.code` | no | nessun conflitto |

Un unique che includa `deleted_at` non funziona: con `deleted_at` NULL i record attivi duplicati sarebbero ammessi
(NULL diverso da NULL in un indice unique).

## Acceptance criteria

1. `down()` delle tabelle elencate lancia eccezione e la tabella resta.
2. Decisione su unique/soft delete documentata.

## Test

`tests/Feature/LegalTablesRollbackTest.php` in Waste, Warehouse e Processing.

## Dev agent record

- Agente: Claude (sessione audit Roosko). Verifica con la harness (Pest + PHPStan).
