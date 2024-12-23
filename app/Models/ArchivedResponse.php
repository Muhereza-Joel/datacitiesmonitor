<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class ArchivedResponse extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'response_id',
        'indicator_id',
        'current',
        'progress',
        'notes',
        'lessons',
        'recommendations',
        'files',
        'status',
        'organisation_id',
        'user_id',
        'original_created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4();
            }
        });
    }

    public function indicator()
    {
        return $this->belongsTo(ArchivedIndicator::class, 'indicator_id', 'indicator_id');
    }


    // Relationship to fetch the user who added the response
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(Files::class, 'response_id', 'response_id');
    }
}
