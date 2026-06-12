<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SystemLogger
{
    public static function logActivity(string $activity, string $module)
    {
        $user = Auth::user();
        if (!$user) return;

        ActivityLog::create([
            'user_id' => $user->id,
            'username' => $user->name,
            'role' => $user->role ? $user->role->display_name : 'User',
            'activity' => $activity,
            'module' => $module,
            'ip_address' => Request::ip(),
            'browser' => Request::header('User-Agent'),
        ]);
    }

    public static function logLogin(?int $userId, string $username, string $status = 'Success')
    {
        LoginHistory::create([
            'user_id' => $userId,
            'username' => $username,
            'login_at' => now(),
            'ip_address' => Request::ip(),
            'device' => self::getDevice(),
            'browser' => self::getBrowser(),
            'status' => $status,
        ]);
    }

    public static function logLogout(int $userId, string $username)
    {
        $history = LoginHistory::where('user_id', $userId)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        if ($history) {
            $history->update(['logout_at' => now()]);
        } else {
            LoginHistory::create([
                'user_id' => $userId,
                'username' => $username,
                'logout_at' => now(),
                'ip_address' => Request::ip(),
                'device' => self::getDevice(),
                'browser' => self::getBrowser(),
                'status' => 'Logout',
            ]);
        }
    }

    public static function logAudit(string $modelName, string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        $user = Auth::user();
        $username = $user ? $user->name : 'System/Guest';
        $userId = $user ? $user->id : null;

        AuditTrail::create([
            'user_id' => $userId,
            'username' => $username,
            'model_name' => $modelName,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    private static function getDevice(): string
    {
        $agent = Request::header('User-Agent', '');
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobi))/i', $agent)) {
            return 'Tablet';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $agent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    private static function getBrowser(): string
    {
        $agent = Request::header('User-Agent', '');
        if (str_contains($agent, 'MSIE') || str_contains($agent, 'Trident/')) return 'Internet Explorer';
        if (str_contains($agent, 'Firefox')) return 'Firefox';
        if (str_contains($agent, 'Chrome')) return 'Chrome';
        if (str_contains($agent, 'Opera') || str_contains($agent, 'OPR')) return 'Opera';
        if (str_contains($agent, 'Safari') && !str_contains($agent, 'Chrome')) return 'Safari';
        if (str_contains($agent, 'Edge') || str_contains($agent, 'Edg/')) return 'Edge';
        return 'Unknown Browser';
    }
}
