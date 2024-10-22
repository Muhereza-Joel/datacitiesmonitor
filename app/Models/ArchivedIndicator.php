<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Uuid;

class ArchivedIndicator extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'indicator_id',
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
        'archive_id',
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
    }

    public function responses()
    {
        return $this->hasMany(ArchivedResponse::class, 'indicator_id', 'indicator_id'); // Use 'indicator_id' as the foreign key
    }


    public function theoryOfChange()
    {
        return $this->belongsTo(TheoryOfChange::class, 'theory_of_change_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function files()
    {
        return $this->hasMany(Files::class, 'indicator_id', 'indicator_id');
    }
}
