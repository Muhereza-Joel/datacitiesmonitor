<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Organisation extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = ['id', 'name', 'logo'];

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

    public function users()
    {
        return $this->hasMany(User::class, 'organisation_id');
    }

    public function theoryOfChange()
    {
        return $this->hasMany(TheoryOfChange::class, 'organisation_id');
    }

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function files()
    {
        return $this->hasMany(Files::class);
    }
}
