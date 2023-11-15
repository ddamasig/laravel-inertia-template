<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Tenant extends \Spatie\Multitenancy\Models\Tenant implements HasMedia
{
    use Notifiable;
    use InteractsWithMedia;

    use UsesLandlordConnection;

    protected $table = 'custom_tenants';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'domain',
        'logo_url',
        'market_plan',
        'has_account_levels',
        'infinity_bonus_enabled',
        'pairing_bonus_fifth_pairs_enabled',
        'pairing_bonus_enabled',
        'direct_referral_bonus_enabled',
        'region_tagging_bonus_enabled',
        'flush_out_enabled',
        'market_plan',
        'color_primary',
        'color_primary_faded',
        'color_error',
        'color_success',
        'color_dark',
        'color_background',
        'status',
        'email',
        'contact_person',
        'mobile_number',
        'additional_address_information',
        'province_id',
        'municipality_id',
    ];

    protected static function booted(): void
    {
        static::creating(fn(Tenant $model) => $model->createDatabase());
    }

    public function createDatabase()
    {
        // add logic to create database
        try {
            $database = DB::statement('CREATE DATABASE mt_' . $this->domain);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

        }
    }

    public function getDatabaseName(): string
    {
        return 'mt_' . $this->domain;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
