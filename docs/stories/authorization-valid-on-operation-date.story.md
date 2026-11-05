---
type: story
tags: [waste, autorizzazioni, conformita, audit-database-r11]
slug: authorization-valid-on-operation-date
title: "Titoli autorizzativi validi alla data dell'operazione, limiti mai sommati"
status: review
phase: REVIEW
owner: Waste
depends_on: []
related: []
created: 2026-11-05
updated: 2026-11-05
---

# Titoli autorizzativi validi alla data dell'operazione, limiti mai sommati

## Story

Come **responsabile ambientale**
voglio che **un conferimento o un trattamento sia verificato contro il titolo valido nel giorno dell'operazione**
e che **un titolo rinnovato non raddoppi i limiti di stoccaggio**
così da **non accettare rifiuti con un'autorizzazione scaduta né sforare i quantitativi autorizzati**.

## Perché / evidenze

Audit database R11 (2026-11-05): il guard di Roosko usa l'`AuthorizedWaste` della regola d'area senza verificare il
titolo; `GetPlantStockSummaryAction` (Roosko) somma i `max_storage_kg` di tutti gli `AuthorizedWaste` dello stesso EER,
anche scaduti. `FindAuthorizedWasteAction` filtrava già i titoli validi, ma solo "oggi" e con il titolo inserito per
ultimo (non quello rilasciato per ultimo).

## Soluzione (in Waste)

- `FindAuthorizedWasteAction::execute(..., ?CarbonInterface $on = null)`: validità alla data dell'operazione;
  con titoli sovrapposti vince il più recente per `issued_at`.
- `AuthorizedWaste::isValidOn()` e scope `validOn()` per chi in altri moduli usa direttamente i codici autorizzati.
- In Waste non c'è alcuna somma di limiti fra titoli (verificato: `GetAuthorizedQuantityUsageAction` lavora su un solo
  codice autorizzato).

## Acceptance criteria

1. Un titolo scaduto, non ancora rilasciato o disattivato alla data dell'operazione non autorizza l'operazione.
2. Con titolo vecchio e rinnovato sullo stesso EER si usa il rinnovato, con i suoi limiti.
3. Lo scope `validOn` esclude i codici di titoli non validi.

## Test

`tests/Feature/AuthorizedWasteActionsTest.php` (gruppo *alla data dell'operazione*).

## Dev agent record

- Agente: Claude (sessione audit Roosko).
- File: `app/Actions/FindAuthorizedWasteAction.php`, `app/Models/AuthorizedWaste.php`, `docs/README.md`.
- Handoff Roosko: `AcceptDeliveryAction` e `StartTreatmentAction` passino la data dell'operazione (`arrived_at`,
  avvio trattamento); `WasteAuthorizationPlacementGuard` e `GetPlantStockSummaryAction` filtrino con
  `AuthorizedWaste::validOn($oggi)` e usino un solo codice per EER invece della somma.
- Non fatto: unique `(plant_authorization_id, ewc_code_id)` su `authorized_wastes` (tabella con soft delete: un unique
  semplice impedirebbe di ricreare un codice eliminato; serve una decisione su dati esistenti).
