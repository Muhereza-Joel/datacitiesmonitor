<?php

namespace App\Models;

use Exception;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        'device_os',
        'device_architecture',
        'device_browser',
        'country',
        'city',
        'region',
        'loc',
        'hostname',
        'org',
        'timezone',
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

    protected static function getIpDetails($ip)
    {

        $cacheKey = 'country_from_ip_' . md5($ip);

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($ip) {
            try {

                $response = Http::get('https://ipinfo.io/' . $ip . '/json');

                if ($response->successful()) {
                    return [
                        'hostname' => $response->json()['hostname'] ?? null,
                        'city' => $response->json()['city'] ?? null,
                        'region' => $response->json()['region'] ?? null,
                        'country' => $response->json()['country'] ?? null,
                        'loc' => $response->json()['loc'] ?? null,
                        'org' => $response->json()['org'] ?? null,
                        'timezone' => $response->json()['timezone'] ?? null,
                    ];
                }

                return null;
            } catch (Exception $e) {

                // Log the exception for debugging purposes
                Log::error('Failed to get country from IP: ' . $ip . ' - Error: ' . $e->getMessage());
                return null;
            }
        });
    }

    protected static function parseUserAgent($userAgent)
    {
        $os = 'Unknown OS';
        $architecture = 'Unknown Architecture';
        $browser = 'Unknown Browser';

        // Simple checks (expand these as needed for more precise parsing)
        if (strpos($userAgent, 'Windows NT 10.0') !== false) {
            $os = 'Windows 10';
        } elseif (strpos($userAgent, 'Mac OS X') !== false) {
            $os = 'macOS';
        }

        if (strpos($userAgent, 'Win64') !== false || strpos($userAgent, 'x64') !== false) {
            $architecture = '64-bit';
        } elseif (strpos($userAgent, 'Win32') !== false || strpos($userAgent, 'x86') !== false) {
            $architecture = '32-bit';
        }

        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        }

        return [
            'os' => $os,
            'architecture' => $architecture,
            'browser' => $browser,
        ];
    }
}
