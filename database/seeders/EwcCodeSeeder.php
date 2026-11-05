<?php

declare(strict_types=1);

namespace Modules\Waste\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Waste\Models\EwcCode;

/**
 * Sottoinsieme verificato dell'Elenco Europeo dei Rifiuti (Allegato D alla Parte IV del D.Lgs. 152/2006,
 * Decisione 2014/955/UE) per impianti di cernita e triturazione: capitoli 15 01, 16 01, 17, 19 12, 20 01, 20 03 01.
 * Il catalogo completo si importa con ImportEwcCodesFromCsvAction.
 *
 * `is_mirror` è vero solo per le coppie in cui la voce pericolosa è definita dalla presenza generica di
 * "sostanze pericolose" / "componenti pericolosi" e la voce non pericolosa rinvia esplicitamente ad essa.
 * Le voci pericolose per natura (amianto, mercurio, CFC, filtri dell'olio, …) non sono a specchio.
 * Sono state omesse le voci la cui qualificazione a specchio non è univoca (es. 15 01 10*, 17 02 04*, 17 04 09*):
 * vanno importate dal catalogo completo con la classificazione adottata dall'impianto.
 */
class EwcCodeSeeder extends Seeder
{
    /**
     * [codice, descrizione, pericoloso, a specchio]
     *
     * @var list<array{0: string, 1: string, 2: bool, 3: bool}>
     */
    public const array CODES = [
        // 15 01 imballaggi (compresi i rifiuti urbani di imballaggio oggetto di raccolta differenziata)
        ['15 01 01', 'imballaggi di carta e cartone', false, false],
        ['15 01 02', 'imballaggi di plastica', false, false],
        ['15 01 03', 'imballaggi in legno', false, false],
        ['15 01 04', 'imballaggi metallici', false, false],
        ['15 01 05', 'imballaggi in materiali compositi', false, false],
        ['15 01 06', 'imballaggi in materiali misti', false, false],
        ['15 01 07', 'imballaggi di vetro', false, false],
        ['15 01 09', 'imballaggi in materia tessile', false, false],
        ['15 01 11', 'imballaggi metallici contenenti matrici solide porose pericolose (ad esempio amianto), compresi i contenitori a pressione vuoti', true, false],

        // 16 01 veicoli fuori uso e rifiuti prodotti dallo smantellamento di veicoli fuori uso e dalla manutenzione di veicoli
        ['16 01 03', 'pneumatici fuori uso', false, false],
        ['16 01 04', 'veicoli fuori uso', true, false],
        ['16 01 06', 'veicoli fuori uso, non contenenti liquidi né altre componenti pericolose', false, false],
        ['16 01 07', 'filtri dell\'olio', true, false],
        ['16 01 08', 'componenti contenenti mercurio', true, false],
        ['16 01 13', 'liquidi per freni', true, false],
        ['16 01 14', 'liquidi antigelo contenenti sostanze pericolose', true, true],
        ['16 01 15', 'liquidi antigelo diversi da quelli di cui alla voce 16 01 14', false, true],
        ['16 01 17', 'metalli ferrosi', false, false],
        ['16 01 18', 'metalli non ferrosi', false, false],
        ['16 01 19', 'plastica', false, false],
        ['16 01 20', 'vetro', false, false],
        ['16 01 22', 'componenti non specificati altrimenti', false, false],
        ['16 01 99', 'rifiuti non specificati altrimenti', false, false],

        // 17 rifiuti delle attività di costruzione e demolizione (compreso il terreno proveniente da siti contaminati)
        ['17 01 01', 'cemento', false, false],
        ['17 01 02', 'mattoni', false, false],
        ['17 01 03', 'mattonelle e ceramiche', false, false],
        ['17 01 06', 'miscugli o scorie di cemento, mattoni, mattonelle e ceramiche, contenenti sostanze pericolose', true, true],
        ['17 01 07', 'miscugli di cemento, mattoni, mattonelle e ceramiche, diversi da quelli di cui alla voce 17 01 06', false, true],
        ['17 02 01', 'legno', false, false],
        ['17 02 02', 'vetro', false, false],
        ['17 02 03', 'plastica', false, false],
        ['17 04 01', 'rame, bronzo, ottone', false, false],
        ['17 04 02', 'alluminio', false, false],
        ['17 04 03', 'piombo', false, false],
        ['17 04 04', 'zinco', false, false],
        ['17 04 05', 'ferro e acciaio', false, false],
        ['17 04 06', 'stagno', false, false],
        ['17 04 07', 'metalli misti', false, false],
        ['17 04 10', 'cavi, impregnati di olio, di catrame di carbone o di altre sostanze pericolose', true, true],
        ['17 04 11', 'cavi, diversi da quelli di cui alla voce 17 04 10', false, true],
        ['17 05 03', 'terra e rocce, contenenti sostanze pericolose', true, true],
        ['17 05 04', 'terra e rocce, diverse da quelle di cui alla voce 17 05 03', false, true],
        ['17 06 01', 'materiali isolanti, contenenti amianto', true, false],
        ['17 06 03', 'altri materiali isolanti contenenti o costituiti da sostanze pericolose', true, true],
        ['17 06 04', 'materiali isolanti, diversi da quelli di cui alle voci 17 06 01 e 17 06 03', false, true],
        ['17 06 05', 'materiali da costruzione contenenti amianto', true, false],
        ['17 08 01', 'materiali da costruzione a base di gesso contaminati da sostanze pericolose', true, true],
        ['17 08 02', 'materiali da costruzione a base di gesso diversi da quelli di cui alla voce 17 08 01', false, true],
        ['17 09 03', 'altri rifiuti dell\'attività di costruzione e demolizione (compresi rifiuti misti) contenenti sostanze pericolose', true, true],
        ['17 09 04', 'rifiuti misti dell\'attività di costruzione e demolizione, diversi da quelli di cui alle voci 17 09 01, 17 09 02 e 17 09 03', false, true],

        // 19 12 rifiuti prodotti dal trattamento meccanico dei rifiuti (ad esempio selezione, triturazione, compattazione, riduzione in pellet) non specificati altrimenti
        ['19 12 01', 'carta e cartone', false, false],
        ['19 12 02', 'metalli ferrosi', false, false],
        ['19 12 03', 'metalli non ferrosi', false, false],
        ['19 12 04', 'plastica e gomma', false, false],
        ['19 12 05', 'vetro', false, false],
        ['19 12 06', 'legno contenente sostanze pericolose', true, true],
        ['19 12 07', 'legno diverso da quello di cui alla voce 19 12 06', false, true],
        ['19 12 08', 'prodotti tessili', false, false],
        ['19 12 09', 'minerali (ad esempio sabbia, rocce)', false, false],
        ['19 12 10', 'rifiuti combustibili (combustibile derivato da rifiuti)', false, false],
        ['19 12 11', 'altri rifiuti (compresi materiali misti) prodotti dal trattamento meccanico dei rifiuti, contenenti sostanze pericolose', true, true],
        ['19 12 12', 'altri rifiuti (compresi materiali misti) prodotti dal trattamento meccanico dei rifiuti, diversi da quelli di cui alla voce 19 12 11', false, true],

        // 20 01 frazioni oggetto di raccolta differenziata (tranne 15 01)
        ['20 01 01', 'carta e cartone', false, false],
        ['20 01 02', 'vetro', false, false],
        ['20 01 08', 'rifiuti biodegradabili di cucine e mense', false, false],
        ['20 01 10', 'abbigliamento', false, false],
        ['20 01 11', 'prodotti tessili', false, false],
        ['20 01 21', 'tubi fluorescenti ed altri rifiuti contenenti mercurio', true, false],
        ['20 01 23', 'apparecchiature fuori uso contenenti clorofluorocarburi', true, false],
        ['20 01 35', 'apparecchiature elettriche ed elettroniche fuori uso, diverse da quelle di cui alla voce 20 01 21 e 20 01 23, contenenti componenti pericolosi', true, true],
        ['20 01 36', 'apparecchiature elettriche ed elettroniche fuori uso, diverse da quelle di cui alle voci 20 01 21, 20 01 23 e 20 01 35', false, true],
        ['20 01 37', 'legno, contenente sostanze pericolose', true, true],
        ['20 01 38', 'legno, diverso da quello di cui alla voce 20 01 37', false, true],
        ['20 01 39', 'plastica', false, false],
        ['20 01 40', 'metalli', false, false],

        // 20 03 altri rifiuti urbani
        ['20 03 01', 'rifiuti urbani non differenziati', false, false],
    ];

    public function run(): void
    {
        foreach (self::CODES as [$code, $description, $isHazardous, $isMirror]) {
            EwcCode::query()->updateOrCreate(
                ['code' => $code],
                [
                    'description' => $description,
                    'is_hazardous' => $isHazardous,
                    'is_mirror' => $isMirror,
                    'is_active' => true,
                ],
            );
        }
    }
}
