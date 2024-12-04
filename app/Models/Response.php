<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Venturecraft\Revisionable\RevisionableTrait;

class Response extends Model
{
    use HasFactory, SoftDeletes, Cachable, RevisionableTrait;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
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
    ];

    protected $revisionable = [
        'indicator_id',
        'current',
        'progress',
        'notes',
        'lessons',
        'recommendations',
        'status',
        'organisation_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4();
            }

            $indicator = $model->indicator;

            // Calculate progress only if target and baseline are not equal
            if ($indicator->baseline === $indicator->target) {
                // When baseline equals target, set progress to 100%
                $model->progress = 100;
            } else {
                // Calculate progress based on the indicator's direction using the formula
                if ($indicator->direction === 'increasing') {
                    if ($model->current < $indicator->baseline || $model->current > $indicator->target) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current state must be between baseline and target for an increasing indicator.'
                        ], 422); // Unprocessable Entity status
                    }
                    $model->progress = (($model->current - $indicator->baseline) / ($indicator->target - $indicator->baseline)) * 100;
                } elseif ($indicator->direction === 'decreasing') {
                    if ($model->current > $indicator->baseline || $model->current < $indicator->target) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Current state must be between baseline and target for a decreasing indicator.'
                        ], 422); // Unprocessable Entity status
                    }
                    $model->progress = (($indicator->baseline - $model->current) / ($indicator->baseline - $indicator->target)) * 100;
                }
            }
        });
    }


    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    // Relationship to fetch the user who added the response
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(Files::class);
    }
}
