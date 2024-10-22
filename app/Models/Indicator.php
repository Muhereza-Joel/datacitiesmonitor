<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

class Indicator extends Model
{
    use HasFactory, SoftDeletes, Searchable, Cachable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'category',
        'name',
        'indicator_title',
        'definition',
        'baseline',
        'target',
        'current_state',  // Allows users to update directly
        'data_source',
        'frequency',
        'responsible',
        'reporting',
        'status',
        'organisation_id',
        'qualitative_progress',
        'direction',
        'theory_of_change_id',
        'is_manually_updated',  // New field to track manual updates
    ];

    // Specify which fields to index for search
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'indicator_title' => $this->indicator_title,
            'definition' => $this->definition,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4();
            }
        });

        static::created(function ($indicator) {
            $indicator->searchable();
        });

        static::updated(function ($indicator) {
            $indicator->searchable();
        });
    }

    // Calculate quantitative progress if not manually updated
    public function getQuantitativeProgressAttribute()
    {
        // Check if baseline equals target
        if ($this->baseline === $this->target) {
            // If baseline equals target, progress is always 100%
            return 100;
        }

        // Calculate progress based on manual update or latest response
        if ($this->is_manually_updated) {
            // Calculate progress based on the indicator's direction
            if ($this->direction === 'decreasing') {
                // For a decreasing indicator
                return (($this->baseline - $this->current_state) / abs($this->baseline - $this->target)) * 100;
            } else {
                // For an increasing indicator (default)
                return (($this->current_state - $this->baseline) / abs($this->target - $this->baseline)) * 100;
            }
        }

        // Use the baseline and target to calculate progress directly if not manually updated
        if ($this->direction === 'decreasing') {
            // For a decreasing indicator
            return (($this->baseline - $this->current_state) / abs($this->baseline - $this->target)) * 100;
        } else {
            // For an increasing indicator (default)
            return (($this->current_state - $this->baseline) / abs($this->target - $this->baseline)) * 100;
        }
    }


    public function responses()
    {
        return $this->hasMany(Response::class, 'indicator_id');
    }

    public function theoryOfChange()
    {
        return $this->belongsTo(TheoryOfChange::class, 'theory_of_change_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function files()
    {
        return $this->hasMany(Files::class);
    }
}
