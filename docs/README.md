# Modulo Waste

Dominio normativo dei rifiuti, riusabile da qualunque software di gestione rifiuti: codici EER, soggetti e unità
locali, titoli autorizzativi degli impianti con i codici autorizzati, formulari di identificazione (FIR), registro
cronologico di carico e scarico. Non sa nulla di magazzino, pesate o lavorazioni (moduli Warehouse, Weighing,
Processing) e non parla con RENTRI (modulo Rentri): espone i dati e le regole che quei moduli usano.

Dipendenze: `Xot` (basi Laraxot), `Geo` (indirizzi delle unità locali, trait `HasAddress`). Libreria aggiuntiva:
`spatie/laravel-medialibrary` (atti autorizzativi). Connessione DB: `waste`.

## Riferimenti normativi

| Tema | Norma |
|---|---|
| Classificazione, Elenco Europeo dei Rifiuti (EER), voci a specchio | D.Lgs. 152/2006, art. 184 e Allegato D alla Parte IV; Decisione 2000/532/CE come sostituita dalla Decisione 2014/955/UE |
| Caratteristiche di pericolo HP1–HP15 | Allegato I alla Parte IV del D.Lgs. 152/2006; Reg. UE 1357/2014 |
| Operazioni di smaltimento D1–D15 / recupero R1–R13 | Allegati B e C alla Parte IV del D.Lgs. 152/2006 (Allegati I e II Dir. 2008/98/CE) |
| Sistema di tracciabilità (RENTRI) | D.Lgs. 152/2006, art. 188-bis; DM 4 aprile 2023 n. 59 |
| Registro cronologico di carico e scarico | D.Lgs. 152/2006, art. 190; DM 59/2023 e relativi modelli |
| Formulario di identificazione (FIR / xFIR) | D.Lgs. 152/2006, art. 193; DM 59/2023 |
| Titoli autorizzativi | art. 208 (autorizzazione unica, c. 15 impianti mobili), artt. 214-216 (procedure semplificate), Parte II Titolo III-bis (AIA) del D.Lgs. 152/2006; D.P.R. 59/2013 (AUA) |

## Modelli

| Modello | Tabella | Note |
|---|---|---|
| `EwcCode` | `ewc_codes` | `code` normalizzato `'19 12 04'` (senza asterisco), `chapter`/`sub_chapter` derivati in salvataggio, `is_hazardous` (asterisco), `is_mirror`, `display_code` con asterisco |
| `Operator` | `operators` | soggetto; `roles` = lista `OperatorRoleEnum`; soft delete |
| `OperatorSite` | `operator_sites` | unità locale; indirizzo = `Address` di Geo (`HasAddress`, morph `model`); `is_plant` = impianto gestito |
| `PlantAuthorization` | `plant_authorizations` | titolo dell'impianto; media collection `document` (PDF/p7m); `isValidOn()`, scope `validOn()` (scadenza inclusa) |
| `AuthorizedWaste` | `authorized_wastes` | codice EER autorizzato: `operations` (lista `WasteOperationEnum`), `max_storage_kg`, `max_annual_kg` |
| `Fir` | `firs` | formulario; stato `FirStatusEnum`; accettazione a destino |
| `Register` | `registers` | registro dell'unità locale (`rentri_identifier` del registro vidimato) |
| `RegisterEntry` | `register_entries` | scrittura; numero progressivo per (registro, anno); immutabile dopo `transmitted_at`; mai cancellata; rettifiche con `rectifies_entry_id`; scope `effective()` esclude le scritture rettificate |

Le FK sono colonne indicizzate senza vincolo (moduli su connessioni diverse). Quantità in kg `decimal(14,3)`.

### Integrità del registro

- **Numerazione**: `AppendRegisterEntryAction` (unico scrittore) blocca la riga del registro con `lockForUpdate`
  in transazione, calcola `max(number)+1` per (registro, anno di `recorded_at`), salva. Vincolo unique
  `(register_id, year, number)` come ultima difesa, retry su collisione o deadlock (`waste.register.transaction_attempts`).
  L'anno è quello di annotazione: una movimentazione di dicembre annotata a gennaio prende il numero del nuovo anno.
- **Immutabilità** (nel modello, `booted()`): registro, anno, numero, data di annotazione e rettificata non cambiano mai;
  dopo `transmitted_at` nessun campo cambia; `delete()` è sempre vietato. Il modulo Rentri marca la trasmissione
  salvando `transmitted_at` e `rentri_transmission_ref` nello stesso `save()` (niente update di massa).
- **Rettifica**: nuova scrittura con il proprio numero, stesso registro e tipo, motivazione in `rectification_reason`;
  una scrittura si rettifica una sola volta (si può rettificare la rettifica). Le scritture sostituite sono escluse dai
  conteggi e non possono essere carichi di riferimento.

<<<<<<< HEAD
=======
### Immutabilità del FIR dopo la bozza

Guardia nel modello (`Fir::booted()`, eccezione `ImmutableFirException`): vale per chiunque, anche per il
super-admin che salta le policy (audit sicurezza M3). Finché il FIR è `draft` si modifica liberamente; dopo:

| Campo | Quando può cambiare |
|---|---|
| `status` | verso uno stato raggiungibile (`FirStatusEnum::canReach`, anche più passaggi in un salvataggio) |
| `acceptance`, `accepted_quantity_kg`, `rejection_reason` | solo nel salvataggio che porta a un esito di accettazione |
| `transport_started_at`, `arrived_at` | se erano vuoti, oppure insieme a un cambio di stato |
| `number` | solo se non era assegnato (numero restituito da RENTRI o del cartaceo) |
| `is_digital` | solo da `false` a `true` |
| tutto il resto (soggetti, EER, quantità, trasporto, note...) | mai |

Un FIR emesso non si elimina (si annulla). `FirResource::canEdit()` nasconde la modifica dopo la bozza anche al
super-admin. Le Actions `ChangeFirStatusAction`, `RecordFirAcceptanceAction` e l'esito RENTRI
(`ApplyRentriTransmissionOutcomeAction` del modulo Rentri: numero e `is_digital`) restano consentiti.
Gli update di massa via query builder non passano dagli eventi Eloquent.

>>>>>>> laraxot/dev
### Regole di validità di una scrittura (`ValidateRegisterEntryDataAction`)

quantità > 0; causale compatibile con il tipo (`RegisterCauseEnum::allowsType`); codice EER attivo; coerenza HP:
codice pericoloso ⇒ almeno una HP, codice non pericoloso ⇒ nessuna HP; operazione R/D obbligatoria per
`to_third_parties` e `internal_treatment`; controparte obbligatoria per `from_third_parties` e `to_third_parties`;
data operazione non futura (tolleranza 5 minuti); carico senza `load_entry_ids`; scarico con almeno un carico dello
stesso registro, stesso codice EER, non rettificato; FIR (se indicato) non annullato e con lo stesso codice EER.

<<<<<<< HEAD
=======
### Titoli autorizzativi validi alla data dell'operazione

Un codice autorizzato (`AuthorizedWaste`) vale solo se il suo titolo è valido **alla data dell'operazione**:
attivo, già rilasciato, non scaduto (scadenza inclusa), non eliminato (`PlantAuthorization::isValidOn`/`scopeValidOn`,
`AuthorizedWaste::isValidOn`/`scopeValidOn`). `FindAuthorizedWasteAction` riceve la data (`$on`, default oggi) e
ignora i titoli non validi in quel giorno (audit database R11).

Con più titoli sullo stesso EER (rinnovo, vecchio titolo ancora attivo) i limiti **non si sommano**: vale il titolo
rilasciato per ultimo (`issued_at`, poi `id`). Chi calcola utilizzi o percentuali (in Roosko: guard di posizionamento
e riepilogo giacenze per EER) deve filtrare con `AuthorizedWaste::validOn($data)` e prendere un solo codice per EER,
non la somma di `max_storage_kg`.

### Soggetti e area clienti

`operators.tenant_id` collega un soggetto a un cliente (tenant del modulo User, `XotData::getTenantClass()`). È l'unica fonte del legame: cantieri (`operator_sites`), FIR (`producer_operator_id`) e conferimenti di Roosko sono del cliente attraverso il soggetto, senza `tenant_id` ripetuti. Relazione `Operator::tenant()`, scope `Operator::ofTenant($tenant)`, campo *Cliente* nel form del soggetto. Story: `Modules/Roosko/docs/stories/01.area-cliente-cantieri.story.md`.

>>>>>>> laraxot/dev
## Enum (valori fissati)

`HazardPropertyEnum` (HP1…HP15), `WasteOperationEnum` (R1…R13, D1…D15; `isRecovery()`, `isDisposal()`,
`isPreliminaryStorage()` = R13/D15), `PhysicalStateEnum`, `QuantityUnitEnum`, `OperatorRoleEnum`,
`AuthorizationTypeEnum`, `FirStatusEnum` (`canTransitionTo()`), `AcceptanceEnum`, `RegisterEntryTypeEnum`,
`RegisterCauseEnum`. Etichette, colori, icone e descrizioni in `lang/{it,en}/{snake_enum}.php`.

## Actions

```php
PostRegisterEntryAction::execute(RegisterEntryData $data): RegisterEntry
RectifyRegisterEntryAction::execute(RegisterEntry $entry, RegisterEntryData $corrected, string $reason): RegisterEntry
<<<<<<< HEAD
FindAuthorizedWasteAction::execute(OperatorSite $plant, EwcCode $ewc, WasteOperationEnum $operation): ?AuthorizedWaste
=======
FindAuthorizedWasteAction::execute(OperatorSite $plant, EwcCode $ewc, WasteOperationEnum $operation, ?CarbonInterface $on = null): ?AuthorizedWaste
>>>>>>> laraxot/dev
GetAuthorizedQuantityUsageAction::execute(AuthorizedWaste $authorized, float $currentStockKg): AuthorizationUsageData
CreateFirAction::execute(FirData $data): Fir
RecordFirAcceptanceAction::execute(Fir $fir, AcceptanceEnum $acceptance, float $acceptedKg, \DateTimeInterface $arrivedAt, ?string $reason = null): Fir
ChangeFirStatusAction::execute(Fir $fir, FirStatusEnum $status): Fir
ImportEwcCodesFromCsvAction::execute(string $path): int
```

Di supporto: `AppendRegisterEntryAction` (numerazione e scrittura), `ValidateRegisterEntryDataAction`,
`EnsureHazardConsistencyAction`, `NormalizeEwcCodeAction`.

Le violazioni di dominio sono eccezioni `Modules\Waste\Exceptions\*` (base `WasteDomainException`, messaggio già
tradotto da `lang/{it,en}/exceptions.php`).

## Data

- `RegisterEntryData` (`from()` accetta snake_case; `fromEntry()` per partire da una scrittura esistente).
- `FirData`.
- `AuthorizationUsageData`: `currentStockKg`, `maxStorageKg`, `storageUsagePct`, `annualLoadedKg`, `maxAnnualKg`,
  `annualUsagePct` (+ `remainingStorageKg()`, `remainingAnnualKg()`, `isStorageExceeded()`, `isAnnualExceeded()`).
  Il carico annuo somma i carichi dell'anno solare (per data operazione) del codice sui registri dell'unità locale.

## Filament (pannello `waste::admin`)

`EwcCodeResource` (header action "Importa CSV"), `OperatorResource` (RM unità locali), `OperatorSiteResource`
(indirizzo con il form di Geo), `PlantAuthorizationResource` (RM codici autorizzati, upload atto), `FirResource`
(creazione via `CreateFirAction`, azioni "Cambia stato" e "Registra accettazione"), `RegisterResource` (RM scritture
in sola lettura), `RegisterEntryResource` (lista + dettaglio, nessuna modifica, azione "Rettifica", export XLS).
Etichette solo da `lang/`.

## Punti di estensione (`config/config.php` → `config('waste.*')`)

- `register.transaction_attempts`: tentativi su deadlock / collisione di numerazione.
- `authorization_document.disk`, `authorization_document.mime_types`: archiviazione degli atti.
- `ewc_import.disk`, `ewc_import.directory`: file temporanei dell'import CSV.

## Seeder

`WasteDatabaseSeeder` → `EwcCodeSeeder` (76 codici EER verificati dei capitoli 15 01, 16 01, 17, 19 12, 20 01,
20 03 01; il catalogo completo si importa da CSV), `OperatorSeeder`, `PlantAuthorizationSeeder`, `RegisterSeeder`
(dati DEMO fittizi). Voci a specchio marcate solo per le coppie "contenenti sostanze pericolose / diversi da quelli di
cui alla voce …"; omesse le voci la cui qualificazione non è univoca (15 01 10\*, 17 02 04\*, 17 04 09\*, 17 03 01\*,
16 01 11\*, 20 01 33\*).

## Cosa è demandato al modulo Rentri

- autenticazione ModI e chiamate alle API RENTRI;
- emissione, firma, accettazione e annullamento dei FIR digitali (xFIR);
- trasmissione delle scritture del registro (valorizza `transmitted_at` e `rentri_transmission_ref`);
- mapping dei nostri enum sulle codifiche RENTRI (stato fisico, causale, unità di misura, tipo accettazione A/P/R,
  stati del formulario);
- vidimazione del registro e dei formulari, sincronizzazione delle codifiche.

## Cosa è demandato al modulo Roosko

Orchestrazione di conferimenti, lotti, trattamenti e uscite: è Roosko a chiamare `FindAuthorizedWasteAction`,
`PostRegisterEntryAction`, `RecordFirAcceptanceAction`, `CreateFirAction` e a calcolare la giacenza istantanea
passata a `GetAuthorizedQuantityUsageAction`.

## Test

`tests/Unit`: formato e validazione del codice EER, regole degli enum (operazioni R/D, HP, transizioni FIR, causali).
`tests/Feature`: numerazione progressiva e cambio anno, numerazione concorrente con processi reali (`pcntl_fork`),
validazioni, immutabilità dopo la trasmissione, rettifica, `FindAuthorizedWasteAction` (scadenza, operazione non
<<<<<<< HEAD
autorizzata, titolo inattivo), utilizzo dell'autorizzazione annuo/istantaneo, FIR, import CSV.
=======
autorizzata, titolo inattivo), utilizzo dell'autorizzazione annuo/istantaneo, FIR, import CSV, immutabilità
del FIR dopo la bozza (`FirImmutabilityTest`).

Storie: [`docs/stories/`](stories/).
>>>>>>> laraxot/dev
