---
type: story
tags: [waste, fir, immutabilita, audit-sicurezza-m3]
slug: fir-immutable-after-draft
title: "FIR immutabile dopo la bozza, anche per il super-admin"
status: review
phase: REVIEW
owner: Waste
depends_on: []
related: []
created: 2026-11-05
updated: 2026-11-05
---

# FIR immutabile dopo la bozza, anche per il super-admin

## Story

Come **responsabile ambientale dell'impianto**
voglio che **un formulario emesso non possa più essere modificato, se non nello stato e nell'esito dell'accettazione**
così che **il FIR in archivio corrisponda sempre a quello che ha viaggiato con il rifiuto** (art. 193 D.Lgs. 152/2006).

## Perché / evidenze

Audit sicurezza M3 (2026-11-05): `XotBasePolicy::before()` restituisce `true` per il super-admin, quindi
`FirPolicy::update` (solo `draft`) e `delete = false` vengono saltati; `FirResource` ha la pagina `edit` e il
modello `Fir` non aveva alcuna guardia: un FIR emesso o trasmesso era modificabile.

## Soluzione

- `Fir::booted()`: evento `updating` che, se lo stato salvato non è `draft`, ammette solo i campi di
  `Fir::POST_DRAFT_MUTABLE` con regole per campo (tabella nel README); evento `deleting` che vieta di eliminare
  un FIR emesso.
- `FirStatusEnum::canReach()` per le transizioni in più passaggi (RecordFirAcceptanceAction passa da `issued`
  a `accepted` in un solo salvataggio).
- `ImmutableFirException` con messaggi tradotti (`exceptions.immutable_fir.*`).
- `FirResource::canEdit()` falso dopo la bozza (anche per il super-admin).

## Acceptance criteria

1. Su un FIR emesso, modificare quantità, EER, soggetti, targa o note lancia `ImmutableFirException`.
2. Cambi di stato e accettazione tramite `ChangeFirStatusAction` e `RecordFirAcceptanceAction` restano consentiti.
3. L'esito RENTRI può assegnare numero e `is_digital` una volta (Rentri `ApplyRentriTransmissionOutcomeAction`).
4. Un FIR emesso non si elimina; uno in bozza sì (a livello di modello; la policy resta `delete = false`).
5. La pagina di modifica non è disponibile dopo la bozza.

## Verifica sulle actions che aggiornano il FIR

| Action | Modulo | Campi | Esito |
|---|---|---|---|
| `ChangeFirStatusAction` | Waste | `status`, `transport_started_at`, `arrived_at` | consentita |
| `RecordFirAcceptanceAction` | Waste | `status`, esito, `arrived_at` | consentita |
| `ApplyRentriTransmissionOutcomeAction::firIssued` | Rentri | `number` (se vuoto), `is_digital` → true, poi stato | consentita |
| `ReceiveDeliveryAction`, `AcceptDeliveryAction`, `RejectDeliveryAction` | Roosko | via le due actions di Waste | consentite |

## Test

`tests/Feature/FirImmutabilityTest.php`.

## Dev agent record

- Agente: Claude (sessione audit Roosko).
- File: `app/Models/Fir.php`, `app/Enums/FirStatusEnum.php`, `app/Exceptions/ImmutableFirException.php`,
  `app/Filament/Resources/FirResource.php`, `lang/{it,en}/exceptions.php`, `docs/README.md`.
- Rischio residuo: gli update di massa (`Fir::query()->update(...)`) non passano dagli eventi; oggi nessun modulo li usa sui FIR.
