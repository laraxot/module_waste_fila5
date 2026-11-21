<?php

declare(strict_types=1);

namespace Modules\Waste\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Waste\Enums\AuthorizationTypeEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Titolo autorizzativo di un impianto (unità locale). L'atto firmato sta nella media collection `document`.
 *
 * @property int $id
 * @property int $operator_site_id
 * @property AuthorizationTypeEnum $type
 * @property string $number
 * @property string $issuing_authority
 * @property CarbonImmutable $issued_at
 * @property CarbonImmutable|null $expires_at
 * @property bool $is_active
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property-read OperatorSite $site
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AuthorizedWaste> $authorizedWastes
 */
class PlantAuthorization extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    public const string DOCUMENT_COLLECTION = 'document';

    public const string DEFAULT_DOCUMENT_DISK = 'local';

    /** @var list<string> */
    protected $fillable = [
        'operator_site_id',
        'type',
        'number',
        'issuing_authority',
        'issued_at',
        'expires_at',
        'is_active',
        'notes',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * @return BelongsTo<OperatorSite, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(OperatorSite::class, 'operator_site_id');
    }

    /**
     * @return HasMany<AuthorizedWaste, $this>
     */
    public function authorizedWastes(): HasMany
    {
        return $this->hasMany(AuthorizedWaste::class);
    }

    public function registerMediaCollections(): void
    {
        $collection = $this->addMediaCollection(self::DOCUMENT_COLLECTION);

        $collection->useDisk(self::documentDisk());

        $mimeTypes = config('waste.authorization_document.mime_types', []);
        if (is_array($mimeTypes) && $mimeTypes !== []) {
            $collection->acceptsMimeTypes(array_values(array_filter($mimeTypes, is_string(...))));
        }
    }

    /**
     * Disco degli atti: quello configurato, altrimenti il disco privato `local`. Mai il default di
     * medialibrary (`public`), servito senza autorizzazione.
     */
    public static function documentDisk(): string
    {
        $disk = config('waste.authorization_document.disk');

        return is_string($disk) && $disk !== '' ? $disk : self::DEFAULT_DOCUMENT_DISK;
    }

    /** Attiva, già rilasciata e non scaduta alla data indicata (la scadenza è inclusa). */
    public function isValidOn(CarbonInterface $date): bool
    {
        $day = $date->toDateString();

        return $this->is_active
            && $this->issued_at->toDateString() <= $day
            && ($this->expires_at === null || $this->expires_at->toDateString() >= $day);
    }

    /**
     * @param  Builder<PlantAuthorization>  $query
     * @return Builder<PlantAuthorization>
     */
    public function scopeValidOn(Builder $query, CarbonInterface $date): Builder
    {
        $day = $date->toDateString();

        return $query
            ->where('is_active', true)
            ->whereDate('issued_at', '<=', $day)
            ->where(static function (Builder $q) use ($day): void {
                $q->whereNull('expires_at')->orWhereDate('expires_at', '>=', $day);
            });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'operator_site_id' => 'integer',
            'type' => AuthorizationTypeEnum::class,
            'issued_at' => 'immutable_date',
            'expires_at' => 'immutable_date',
            'is_active' => 'boolean',
        ]);
    }
}
