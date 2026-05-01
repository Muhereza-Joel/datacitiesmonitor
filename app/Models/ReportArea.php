<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class ReportArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        "id",
        "project_id",
        "report_id",
        "area_of_focus_id",
        "objective",
        "activities_conducted",
        "achievements",
        "challenges",
        "risks",
        "opportunities",
        "recommendations",
        "lessons_learned",
        "stakeholder_feedback",
        "action_plans",
        "status",
        "organisation_id",
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
}
