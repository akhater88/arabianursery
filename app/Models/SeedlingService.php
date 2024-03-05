<?php

namespace App\Models;

use App\Enums\SeedlingServiceStatuses;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SeedlingService extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    const TYPE_PERSONAL = 1;
    const TYPE_FARMER = 2;

    public $isUpdated;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => SeedlingServiceStatuses::class,
        'cash' => 'object'
    ];

    //  ----------    Relationships    ----------
    public function seedType(): belongsTo
    {
        return $this->belongsTo(SeedType::class);
    }

    public function nursery(): belongsTo
    {
        return $this->belongsTo(Nursery::class);
    }

    public function nurseryUser(): belongsTo
    {
        return $this->belongsTo(NurseryUser::class);
    }

    public function farmUser(): belongsTo
    {
        return $this->belongsTo(FarmUser::class);
    }

    public function seedlingPurchaseRequests(): hasMany
    {
        return $this->hasMany(SeedlingPurchaseRequest::class);
    }

    public function images(): hasMany
    {
        return $this->hasMany(SeedlingServiceImage::class);
    }


    /* -------------- Scopes -------------- */
    public function scopePersonal($query)
    {
        return $query->where('type', self::TYPE_PERSONAL);
    }

    //  ----------    Accessor & Mutators    ----------
    public function optionName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->seedType->name} - {$this->seed_class} - {$this->created_at->format('Y-m-d')}"
        );
    }

    //  ----------    Tools    ----------
    public function syncImages($uploaded_images)
    {
        $uploaded_images = collect($uploaded_images);
        $this->isUpdated = false;
        $this->images->each(function ($image) use ($uploaded_images) {
            if ($uploaded_images->doesntContain($image->name)) {
                Storage::delete($image->path);
                $image->delete();
                $this->isUpdated = true;
            }
        });

        $uploaded_images->each(function ($uploaded_image) {
            if($this->images->contains('name', $uploaded_image)) {
                return;
            }

            if(Storage::directoryMissing("seedling-services/{$this->id}")){
                Storage::makeDirectory("seedling-services/{$this->id}");
            }

            if(Storage::fileExists("tmp/uploads/{$uploaded_image}")){
                Storage::move("tmp/uploads/{$uploaded_image}", "seedling-services/{$this->id}/{$uploaded_image}");

                $this->images()->create([
                    'name' => $uploaded_image,
                    'path' => "seedling-services/{$this->id}/{$uploaded_image}"
                ]);
                $this->isUpdated = true;
            }
        });
        return $this->isUpdated;
    }

    public function agriculturalSupplyStoreUser(): belongsTo
    {
        return $this->belongsTo(AgriculturalSupplyStoreUser::class);
    }

    /**
     * Get all of the seedling service's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    /**
     * The seedling that shared to the nurseries.
     */
    public function sharedWithNurseries(): BelongsToMany
    {
        return $this->belongsToMany(Nursery::class,'seedling_shared_with_nurseries', 'seedling_id', 'nursery_id')->withTimestamps();
    }
}
