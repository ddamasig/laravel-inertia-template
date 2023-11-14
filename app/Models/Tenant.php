<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Tenant extends BaseModel
//class Tenant extends \Spatie\Multitenancy\Models\Tenant implements HasMedia
{
    use Notifiable;
    use InteractsWithMedia;
//    use UsesLandlordConnection;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'logo_url',
        'market_plan',
        'has_account_levels',
        'has_infinity_bonus',
        'has_fifth_pairs',
        'has_pairing_bonus',
        'has_direct_referral_bonus',
        'has_region_tagging_bonus',
        'has_flush_out',
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
    ];

//    protected static function booted()
//    {
//        static::creating(fn(Tenant $model) => $model->createDatabase());
//    }

//    public function createDatabase()
//    {
//        // add logic to create database
//        DB::raw('CREATE DATABASE ' . $this->name);
//    }

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
