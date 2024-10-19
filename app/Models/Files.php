<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Files extends Model
{
    use HasFactory, SoftDeletes;

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


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Uuid::uuid4();
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
