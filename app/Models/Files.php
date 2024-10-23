<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Files extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'response_id',
        'organisation_id',
        'indicator_id',
        'name',
        'original_name',
        'mime_type',
        'size',
        'path',
        'extension',
    ];


    // Automatically generate UUID when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4();
            }
        });
    }

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
