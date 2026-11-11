---
type: story
slug: fir-cartaceo-numero-trasportatore
title: "FIR cartaceo: non si emette senza numero e trasportatore"
status: done
phase: DONE
owner: Waste
related:
  - "../../../Roosko/docs/stories/flow-walkthrough-fixes.story.md"
created: 2026-11-11
updated: 2026-11-11
---

# FIR cartaceo: non si emette senza numero e trasportatore

## Perché

Nella prova del flusso di uscita (Roosko → Spedisci) il FIR nasce in bozza nel pannello Rifiuti, come previsto.
Con «Cambia stato» → Emesso, però, passava allo stato emesso anche senza numero né trasportatore. Dopo la bozza il
formulario non si modifica più (`FirResource::canEdit`), quindi un FIR cartaceo emesso così restava senza numero
del modulo vidimato e senza chi lo porta.

## Fatto

- `ChangeFirStatusAction`: un FIR cartaceo non esce dalla bozza verso `issued` o `in_transit` se mancano
  `number` o `transporter_operator_id`. Il messaggio dice cosa completare (`invalid_fir_status_transition.not_ready_for_transport`).
- Il FIR digitale non cambia comportamento: il numero lo assegna RENTRI e l'emissione la registra
  `ApplyRentriTransmissionOutcomeAction`.
- `FirImmutabilityTest`: il FIR emesso senza numero del test ora è digitale (il numero arriva dopo da RENTRI);
  aggiunti i due casi del cartaceo.

## Verifica

Suite di Waste 147, Roosko 125 e Rentri 58 verdi; PHPStan 0.
