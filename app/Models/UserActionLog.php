<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserActionLog extends Model
{
    use HasFactory, SoftDeletes, Cachable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'uuid';


    protected $fillable = [
        'id',
        'user_id', // User who performed the action
        'action', // Description of the action
        'ip_address', // User's IP address
        'resource_type', // Type of the resource (e.g., 'Indicator')
        'resource_id', // ID of the specific resource
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserRecentActivities($userId)
    {
        // Fetch recent indicators, ToCs, responses, and logins
        $recentIndicators = UserActionLog::recentIndicators($userId)->get();
        $recentToCs = UserActionLog::recentToCs($userId)->get();
        $recentResponses = UserActionLog::recentResponses($userId)->get();
        $lastLogins = UserActionLog::lastLogins($userId)->get();

        return [
            'recentIndicators' => $recentIndicators,
            'recentToCs' => $recentToCs,
            'recentResponses' => $recentResponses,
            'lastLogins' => $lastLogins,
        ];
    }

    public function scopeRecentIndicators($query, $userId, $limit = 3)
    {
        return $query->where('user_id', $userId)
            ->where('action', 'visit_indicator')
            ->join('indicators', 'user_action_logs.resource_id', '=', 'indicators.id')
            ->select(
                'indicators.id as indicator_id',
                'indicators.indicator_title',
                DB::raw('MAX(user_action_logs.created_at) as created_at')
            )
            ->groupBy('indicators.id', 'indicators.indicator_title')
            ->orderBy('created_at', 'desc')
            ->take($limit);
    }


    public function scopeRecentToCs($query, $userId, $limit = 3)
    {
        return $query->where('user_id', $userId)
            ->where('action', 'visit_toc')
            ->join('theory_of_changes', 'user_action_logs.resource_id', '=', 'theory_of_changes.id')
            ->select(
                'theory_of_changes.id as resource_id',
                'theory_of_changes.title as toc_title',
                 DB::raw('MAX(user_action_logs.created_at) as created_at')
            )
            ->groupBy('theory_of_changes.id', 'theory_of_changes.title')
            ->orderBy('created_at', 'desc')
            ->take($limit);
    }


    public function scopeRecentResponses($query, $userId, $limit = 3)
    {
        return $query->where('user_id', $userId)
            ->where('action', 'visit_response')
            ->latest()
            ->take($limit);
    }

    public function scopeLastLogins($query, $userId, $limit = 3)
    {
        return $query->where('user_id', $userId)
            ->where('action', 'User logged in')
            ->latest()
            ->take($limit);
    }
}
