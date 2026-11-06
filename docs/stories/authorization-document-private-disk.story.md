---
type: story
tags: [waste, sicurezza, upload, audit-sicurezza-m5]
slug: authorization-document-private-disk
title: "Atti autorizzativi su disco privato per default"
status: review
phase: REVIEW
owner: Waste
depends_on: []
related: []
created: 2026-11-05
updated: 2026-11-05
---

# Atti autorizzativi su disco privato per default

## Story

Come **responsabile ambientale**
voglio che **gli atti autorizzativi caricati non siano scaricabili da chi conosce o indovina l'URL**
così che **documenti riservati dell'impianto restino accessibili solo dal pannello**.

## Perché / evidenze

Audit sicurezza M5: `PlantAuthorization` usava `WASTE_AUTHORIZATION_DOCUMENT_DISK` se impostata, altrimenti il disco
di default di medialibrary (`public`): URL `/storage/{id}/{nome}` con id sequenziali.

## Soluzione

- `config('waste.authorization_document.disk')` default `local` (privato, `storage/app/private`).
- `PlantAuthorization::documentDisk()`: disco configurato o `local`, mai il default di medialibrary.
- Form: `->disk(documentDisk())->visibility('private')`: Filament apre/scarica con URL temporanei firmati.

## Acceptance criteria

1. Senza variabile d'ambiente la collection `document` usa il disco `local`.
2. Un disco configurato viene rispettato.

## Test

`tests/Feature/AuthorizationDocumentDiskTest.php`.

## Dev agent record

- Agente: Claude (sessione audit Roosko).
- File: `config/config.php`, `app/Models/PlantAuthorization.php`, `PlantAuthorizationForm.php`, `docs/README.md`.
- Rischio residuo: atti già caricati sul disco `public` restano lì; vanno spostati (es. `media-library:move` o
  ricaricati). Il disco `local` deve avere `serve => true` per gli URL temporanei (vero nella base Roosko).
