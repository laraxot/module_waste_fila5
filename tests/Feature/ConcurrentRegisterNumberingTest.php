<?php

declare(strict_types=1);

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Modules\Waste\Actions\PostRegisterEntryAction;
use Modules\Waste\Models\EwcCode;
use Modules\Waste\Models\Operator;
use Modules\Waste\Models\Register;
use Modules\Waste\Models\RegisterEntry;
use Modules\Waste\Tests\Support\WasteFixtures;
use Modules\Waste\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\getmypid;
use function Safe\glob;
use function Safe\posix_kill;
use function Safe\touch;
use function Safe\unlink;

uses(TestCase::class);

/**
 * Sostituisce la connessione risolta `$name` senza disconnetterla: nel TestCase le connessioni sqlite
 * condividono lo stesso oggetto (e la stessa transazione di test), un DB::purge la chiuderebbe per tutte.
 */
function swapResolvedConnection(string $name, ?Connection $connection): ?Connection
{
    $manager = app('db');
    if (! $manager instanceof DatabaseManager) {
        return null;
    }

    $property = new ReflectionProperty($manager, 'connections');
    /** @var array<string, Connection> $connections */
    $connections = $property->getValue($manager);
    $previous = $connections[$name] ?? null;
    unset($connections[$name]);
    if ($connection !== null) {
        $connections[$name] = $connection;
    }
    $property->setValue($manager, $connections);

    return $previous;
}

/**
 * Connessione del test: file sqlite dedicato, oppure MariaDB/MySQL se `WASTE_CONCURRENCY_DRIVER` vale
 * `mariadb` o `mysql` (job CI dedicato). Su MariaDB/MySQL `lockForUpdate` blocca davvero la riga del registro:
 * e' il caso che sqlite non puo' provare (rischio R4 del rapporto finale).
 *
 * @return array<string, mixed>
 */
function concurrencyConnectionConfig(string $sqliteFile): array
{
    $driver = getenv('WASTE_CONCURRENCY_DRIVER');
    if ($driver === 'mariadb' || $driver === 'mysql') {
        $value = static fn (string $name, string $default): string => \is_string(getenv($name)) && getenv($name) !== '' ? (string) getenv($name) : $default;

        return [
            'driver' => $driver,
            'host' => $value('WASTE_CONCURRENCY_HOST', '127.0.0.1'),
            'port' => $value('WASTE_CONCURRENCY_PORT', '3306'),
            'database' => $value('WASTE_CONCURRENCY_DATABASE', 'waste_concurrency'),
            'username' => $value('WASTE_CONCURRENCY_USERNAME', 'root'),
            'password' => $value('WASTE_CONCURRENCY_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ];
    }

    return [
        'driver' => 'sqlite',
        'database' => $sqliteFile,
        'prefix' => '',
        'foreign_key_constraints' => false,
        'busy_timeout' => 15000,
    ];
}

/*
 * Concorrenza reale: N processi figli (pcntl_fork) annotano scritture sullo stesso registro, ognuno con la propria
 * connessione a un file sqlite dedicato (fuori dalla transazione di test). Atteso: numeri 1..N*M senza buchi né
 * duplicati. Su sqlite `lockForUpdate` è un no-op: la correttezza la garantiscono la serializzazione delle scritture
 * del DB, il vincolo unique e i retry di AppendRegisterEntryAction (su MySQL/PostgreSQL anche il lock di riga).
 */
test('concurrent processes posting on the same register get unique consecutive numbers', function (): void {
    if (! function_exists('pcntl_fork') || ! function_exists('posix_kill')) {
        $this->markTestSkipped('pcntl/posix not available');
    }

    $children = 4;
    $perChild = 10;
    $file = sys_get_temp_dir().'/waste_concurrency_'.getmypid().'_'.bin2hex(random_bytes(4)).'.sqlite';
    touch($file);

    $originalConfig = config('database.connections.waste');
    config([
        'database.connections.waste' => concurrencyConnectionConfig($file),
        'waste.register.transaction_attempts' => 100,
    ]);
    $originalConnection = swapResolvedConnection('waste', null);

    try {
        WasteFixtures::migrate();
        $register = Register::factory()->createOne();
        $ewc = EwcCode::factory()->createOne();
        $producer = Operator::factory()->createOne();
        DB::purge('waste');

        $pids = [];
        for ($i = 0; $i < $children; $i++) {
            $pid = pcntl_fork();
            if ($pid === -1) {
                $this->fail('fork failed');
            }
            if ($pid === 0) {
                // Figlio: connessione propria al file; SIGKILL a fine lavoro per non eseguire gli shutdown handler del padre.
                $errors = '';
                for ($j = 0; $j < $perChild; $j++) {
                    try {
                        app(PostRegisterEntryAction::class)->execute(WasteFixtures::loadData($register, $ewc, counterpart: $producer));
                    } catch (Throwable $e) {
                        $errors .= $e::class.': '.$e->getMessage()."\n";
                    }
                }
                file_put_contents($file.'.child'.$i, $errors);
                posix_kill(getmypid(), SIGKILL);
            }
            $pids[] = $pid;
        }

        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }

        $errors = '';
        for ($i = 0; $i < $children; $i++) {
            $errors .= is_file($file.'.child'.$i) ? (string) file_get_contents($file.'.child'.$i) : "child {$i} produced no report\n";
        }
        Assert::assertSame('', $errors);

        $numbers = RegisterEntry::query()
            ->where('register_id', $register->id)
            ->orderBy('number')
            ->pluck('number')
            ->map(static fn (mixed $n): int => is_numeric($n) ? (int) $n : 0)
            ->all();

        Assert::assertSame(range(1, $children * $perChild), $numbers);
    } finally {
        DB::purge('waste');
        config(['database.connections.waste' => $originalConfig]);
        swapResolvedConnection('waste', $originalConnection);
        foreach (glob($file.'*') as $path) {
            if (is_string($path) && is_file($path)) {
                unlink($path);
            }
        }
    }
});
