---
type: story
slug: authorized-operations-options
title: "Operazioni R/D autorizzate per codice EER, e FIR non in bozza senza Modifica"
status: done
owner: Waste
created: 2026-11-10
updated: 2026-11-10
---

# Operazioni R/D autorizzate per codice EER

## Story

Come operatore che avvia un trattamento dal telefono voglio vedere solo le operazioni che l'impianto puo'
svolgere su quel rifiuto, cosi' da non scorrere 30 voci e non sceglierne una vietata.

## Fatto

- `GetAuthorizedOperationsAction(plant, ewc, ?on)`: unione delle operazioni dei titoli validi alla data per il
  codice EER, nell'ordine dell'enum. Stesse regole di validita' di `FindAuthorizedWasteAction`, che resta il controllo
  vero dentro le action. Test in `AuthorizedWasteActionsTest`.
- `CreateFirAction`: il messaggio "non ha il ruolo richiesto" dice il ruolo tradotto ("destinatario"), non `recipient`.
- Elenco e dettaglio FIR: "Modifica" solo per le bozze, anche per il super-admin; prima portava a una pagina 403.
- Policy: `RequiresOperationalPermissions` (il ruolo `waste::admin` apre il pannello, i permessi decidono le azioni).
