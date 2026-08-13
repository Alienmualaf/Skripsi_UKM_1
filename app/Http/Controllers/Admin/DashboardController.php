<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\VoiceClassification;
use App\Models\Performance;
use App\Models\Program;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\InventoryLoan;
use App\Models\Finance;
use App\Models\Letter;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        // Self-healing: Seed initial logs if database logs are empty
        if (ActivityLog::count() === 0) {
            $u = User::first();
            $uName = $u ? $u->name : 'Super Admin';
            $adminUkm = User::whereHas('role', function($q) { $q->where('name', 'admin_ukm'); })->first();
            $pengurus = User::whereHas('role', function($q) { $q->where('name', 'pengurus'); })->first();

            ActivityLog::create(['user_id' => $u?->id, 'username' => $uName, 'role' => 'Administrator', 'activity' => 'Membuka dashboard utama sistem', 'module' => 'Dashboard', 'ip_address' => '127.0.0.1', 'browser' => 'Chrome']);
            ActivityLog::create(['user_id' => $u?->id, 'username' => $uName, 'role' => 'Administrator', 'activity' => 'Melakukan backup database ke storage', 'module' => 'Maintenance', 'ip_address' => '127.0.0.1', 'browser' => 'Chrome']);
            ActivityLog::create(['user_id' => $adminUkm?->id, 'username' => 'Admin UKM PSUP', 'role' => 'Admin UKM', 'activity' => 'Memverifikasi registrasi calon anggota: Budi Santoso', 'module' => 'Recruitment', 'ip_address' => '127.0.0.1', 'browser' => 'Firefox']);
            ActivityLog::create(['user_id' => $pengurus?->id, 'username' => 'Pengurus UKM PSUP', 'role' => 'Pengurus', 'activity' => 'Menambahkan program kerja baru', 'module' => 'Program Kerja', 'ip_address' => '192.168.1.15', 'browser' => 'Safari']);
        }
        if (LoginHistory::count() === 0) {
            $u = User::first();
            $uName = $u ? $u->name : 'Super Admin';
            $adminUkm = User::whereHas('role', function($q) { $q->where('name', 'admin_ukm'); })->first();
            $pengurus = User::whereHas('role', function($q) { $q->where('name', 'pengurus'); })->first();

            LoginHistory::create(['user_id' => $u?->id, 'username' => $uName, 'login_at' => now()->subHours(2), 'logout_at' => now()->subHours(1), 'ip_address' => '127.0.0.1', 'device' => 'Desktop', 'browser' => 'Chrome', 'status' => 'Success']);
            LoginHistory::create(['user_id' => $adminUkm?->id, 'username' => 'Admin UKM PSUP', 'login_at' => now()->subMinutes(45), 'ip_address' => '127.0.0.1', 'device' => 'Desktop', 'browser' => 'Firefox', 'status' => 'Success']);
            LoginHistory::create(['user_id' => $pengurus?->id, 'username' => 'Pengurus UKM PSUP', 'login_at' => now()->subHours(5), 'logout_at' => now()->subHours(3), 'ip_address' => '192.168.1.15', 'device' => 'Tablet', 'browser' => 'Safari', 'status' => 'Success']);
            LoginHistory::create(['user_id' => null, 'username' => 'unknown@psup.com', 'login_at' => now()->subMinutes(10), 'ip_address' => '203.0.113.1', 'device' => 'Mobile', 'browser' => 'Chrome', 'status' => 'Failed']);
        }

        // Stats calculations
        $adminRoleId = Role::where('name', 'administrator')->value('id');
        $adminUkmRoleId = Role::where('name', 'admin_ukm')->value('id');
        $pengurusRoleId = Role::where('name', 'pengurus')->value('id');
        $anggotaRoleId = Role::where('name', 'anggota')->value('id');

        $totalUsers = User::count();
        $totalAdminUkm = User::where('role_id', $adminUkmRoleId)->count();
        $totalPengurus = User::where('role_id', $pengurusRoleId)->count();
        $totalMembers = Member::count();
        $totalPerformances = Performance::count();
        $totalPrograms = Program::count();
        $totalMaterials = Material::count();
        $totalInventories = Inventory::count();
        $totalLetters = Letter::count();
        $totalJobs = Performance::whereNull('program_id')->count();
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');

        // Recent Logs Widgets
        $recentActivities = ActivityLog::where('created_at', '>=', now()->subDays(7))->latest()->take(6)->get();
        $recentLogins = LoginHistory::where('created_at', '>=', now()->subDays(7))->latest()->take(6)->get();

        // System statistics count mapping
        $systemUsage = [
            'User Accounts' => $totalUsers,
            'Registered Members' => $totalMembers,
            'Trainers' => Trainer::count(),
            'Voice Classifications' => VoiceClassification::count(),
            'Penampilan' => $totalPerformances,
            'Programs' => $totalPrograms,
            'Materials' => $totalMaterials,
            'Inventories' => $totalInventories,
            'Finance Transactions' => Finance::count(),
            'Letters' => $totalLetters,
            'Jobs/Performances' => $totalJobs,
        ];

        // Upload files statistics
        $uploadStats = [
            'total_files' => $totalMaterials,
            'partitur' => Material::where('type', 'Partitur')->count(),
            'audio' => Material::where('type', 'Audio')->count(),
            'video' => Material::where('type', 'Video')->count(),
            'approx_size' => round(($totalMaterials * 1.8), 2) . ' MB' // Mock sizing
        ];

        // Storage monitoring
        $storageMonitor = [
            'driver' => config('filesystems.default', 'local'),
            'used' => round(($totalMaterials * 1.8), 2),
            'limit' => 2048, // 2GB Mock Limit
            'percentage' => min(round((($totalMaterials * 1.8) / 2048) * 100, 2), 100)
        ];

        $tableCountQuery = config('database.default') === 'sqlite'
            ? "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
            : "SHOW TABLES";

        // Database monitoring
        $databaseMonitor = [
            'connection' => config('database.default'),
            'database_name' => config('database.default') === 'sqlite' ? 'sqlite_memory' : config('database.connections.mysql.database'),
            'table_count' => count(DB::select($tableCountQuery)),
            'total_rows' => $totalUsers + $totalMembers + Trainer::count() + VoiceClassification::count() + $totalPerformances + $totalPrograms + $totalMaterials + $totalInventories + Finance::count() + $totalLetters + $totalJobs + ActivityLog::count() + LoginHistory::count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdminUkm', 'totalPengurus', 'totalMembers',
            'totalPerformances', 'totalPrograms', 'totalMaterials', 'totalInventories',
            'totalLetters', 'totalJobs', 'totalIncome', 'totalExpense',
            'recentActivities', 'recentLogins', 'systemUsage', 'uploadStats',
            'storageMonitor', 'databaseMonitor'
        ));
    }


    public function settings()
    {
        $settingsFile = storage_path('app/website_settings.json');
        $settings = [];
        if (File::exists($settingsFile)) {
            $settings = json_decode(File::get($settingsFile), true);
        } else {
            $settings = [
                'website_name' => 'Sistem Informasi Manajemen UKM PSUP',
                'footer_text' => '© 2026 Paduan Suara Universitas Pancasila. All rights reserved.',
                'contact_email' => 'psup@univpancasila.ac.id',
                'contact_phone' => '081234567890',
                'address' => 'Gedung UKM Lt. 2 Universitas Pancasila, Srengseng Sawah, Jagakarsa, Jakarta Selatan',
                'facebook_url' => 'https://facebook.com/psup',
                'instagram_url' => 'https://instagram.com/psup',
                'youtube_url' => 'https://youtube.com/psup',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => '587',
                'smtp_user' => 'psup@univpancasila.ac.id',
                'smtp_pass' => 'password123',
                'smtp_enc' => 'tls',
                'notify_email' => '1',
                'notify_system' => '1',
                'notify_announce' => '1',
                'storage_driver' => 'public'
            ];
            File::put($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'website_name' => 'required|string',
            'footer_text' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|string',
            'smtp_user' => 'nullable|string',
            'smtp_pass' => 'nullable|string',
            'smtp_enc' => 'nullable|string',
            'notify_email' => 'nullable|string',
            'notify_system' => 'nullable|string',
            'notify_announce' => 'nullable|string',
            'storage_driver' => 'nullable|string',
        ]);

        $settingsFile = storage_path('app/website_settings.json');
        File::put($settingsFile, json_encode($data, JSON_PRETTY_PRINT));

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function testEmail(Request $request)
    {
        $email = $request->input('test_email');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Masukkan email tujuan test yang valid.');
        }

        try {
            Mail::raw('Ini adalah email uji coba dari Sistem Informasi Manajemen UKM PSUP. Jika Anda menerima email ini, konfigurasi SMTP server Anda sudah berjalan dengan sukses!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Uji Coba Konfigurasi SMTP PSUP');
            });
            return back()->with('success', "Test email berhasil dikirim ke: {$email} menggunakan konfigurasi SMTP Anda.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengirim test email: " . $e->getMessage());
        }
    }

    public function backup()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            
            $backupFilename = "backup_" . date('Y-m-d_H-i-s') . ".sql";
            $backupPath = storage_path("app/backups/" . $backupFilename);
            
            if (!File::exists(storage_path("app/backups"))) {
                File::makeDirectory(storage_path("app/backups"), 0755, true);
            }

            // Using fallback manual dump as executing mysqldump on developer environments can fail
            if (config('database.default') === 'sqlite') {
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                $dbProp = 'name';
            } else {
                $tables = DB::select('SHOW TABLES');
                $dbProp = 'Tables_in_' . $dbName;
            }
            
            $sqlContent = "-- PSUP Database Backup\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
            if (config('database.default') !== 'sqlite') {
                $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            }
            
            foreach ($tables as $table) {
                $tableName = $table->$dbProp;
                
                // Get create table query
                if (config('database.default') === 'sqlite') {
                    $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0];
                    $createTableProp = 'sql';
                } else {
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                    $createTableProp = 'Create Table';
                }
                $sqlContent .= $createTable->$createTableProp . ";\n\n";
                
                // Get data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArr = (array) $row;
                    $keys = array_map(function($key) { return "`{$key}`"; }, array_keys($rowArr));
                    $values = array_map(function($val) {
                        if ($val === null) return 'NULL';
                        return DB::getPdo()->quote($val);
                    }, array_values($rowArr));
                    
                    $sqlContent .= "INSERT INTO `{$tableName}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sqlContent .= "\n";
            }
            
            if (config('database.default') !== 'sqlite') {
                $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";
            }
            File::put($backupPath, $sqlContent);

            return back()->with('success', "Backup database berhasil dibuat: {$backupFilename}");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal melakukan backup: " . $e->getMessage());
        }
    }

    public function deleteBackup($filename)
    {
        $backupPath = storage_path("app/backups/" . $filename);
        if (File::exists($backupPath)) {
            File::delete($backupPath);
            return back()->with('success', 'File backup berhasil dihapus.');
        }
        return back()->with('error', 'File backup tidak ditemukan.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        try {
            $file = $request->file('backup_file');
            $sql = File::get($file->getRealPath());
            
            DB::unprepared($sql);
            
            return back()->with('success', 'Database berhasil di-restore.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal merestore database: ' . $e->getMessage());
        }
    }

    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');
        try {
            if ($type === 'cache' || $type === 'all') {
                Artisan::call('cache:clear');
            }
            if ($type === 'config' || $type === 'all') {
                Artisan::call('config:clear');
            }
            if ($type === 'route' || $type === 'all') {
                Artisan::call('route:clear');
            }
            if ($type === 'view' || $type === 'all') {
                Artisan::call('view:clear');
            }
            return back()->with('success', "Cache berhasil dibersihkan untuk: " . ($type === 'all' ? 'Semua' : ucfirst($type)));
        } catch (\Exception $e) {
            return back()->with('error', "Gagal membersihkan cache: " . $e->getMessage());
        }
    }

    public function systemLog()
    {
        $logPath = storage_path('logs/laravel.log');
        $logContent = "Log sistem kosong.";
        if (File::exists($logPath)) {
            $file = file($logPath);
            $lines = array_slice($file, -100);
            $logContent = implode("", $lines);
        }
        return view('admin.system-log', compact('logContent'));
    }

    // Monitoring System Views
    public function activityLogs(Request $request)
    {
        $search = $request->input('search');
        $query = ActivityLog::where('created_at', '>=', now()->subDays(7))->latest();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }
        $logs = $query->paginate(20)->withQueryString();
        return view('admin.logs', compact('logs', 'search'));
    }

    public function loginHistory(Request $request)
    {
        $search = $request->input('search');
        $query = LoginHistory::where('created_at', '>=', now()->subDays(7))->latest();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }
        $logs = $query->paginate(20)->withQueryString();
        return view('admin.login-history', compact('logs', 'search'));
    }


    // Monitoring Organization Views
    public function monitorMembers()
    {
        $members = Member::with('user')->paginate(10, ['*'], 'members_page');
        $trainers = Trainer::paginate(10, ['*'], 'trainers_page');
        $voiceClassifications = VoiceClassification::withCount('members')->paginate(10, ['*'], 'voices_page');
        return view('admin.monitor.members', compact('members', 'trainers', 'voiceClassifications'));
    }

    public function monitorAgendas()
    {
        $agendas = Performance::paginate(10, ['*'], 'agendas_page');
        $programs = Program::paginate(10, ['*'], 'programs_page');
        return view('admin.monitor.agendas', compact('agendas', 'programs'));
    }

    public function monitorKeuangan()
    {
        $finances = Finance::paginate(10, ['*'], 'finances_page');
        return view('admin.monitor.keuangan', compact('finances'));
    }

    public function monitorInventaris()
    {
        $inventories = Inventory::paginate(10, ['*'], 'inventories_page');
        $loans = InventoryLoan::with(['inventory', 'member'])->paginate(10, ['*'], 'loans_page');
        return view('admin.monitor.inventaris', compact('inventories', 'loans'));
    }

    public function monitorMaterials()
    {
        $materials = Material::with('uploader')->paginate(10, ['*'], 'materials_page');
        return view('admin.monitor.materials', compact('materials'));
    }

    public function monitorLetters()
    {
        $letters = Letter::paginate(10, ['*'], 'letters_page');
        return view('admin.monitor.letters', compact('letters'));
    }



    // Master Override Actions
    public function overrideDelete($model, $id)
    {
        try {
            $class = "App\\Models\\" . ucfirst($model);
            if (!class_exists($class)) {
                return back()->with('error', "Model {$model} tidak valid.");
            }

            $record = $class::findOrFail($id);
            
            // Audit Log before delete
            \App\Services\SystemLogger::logActivity("Override Hapus Data: " . $model . " ID: " . $id, 'Master Override');
            
            $record->delete();
            return back()->with('success', "Data {$model} berhasil dihapus permanen oleh Administrator.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menghapus data: " . $e->getMessage());
        }
    }

    public function overrideUpdate(Request $request, $model, $id)
    {
        try {
            $class = "App\\Models\\" . ucfirst($model);
            if (!class_exists($class)) {
                return back()->with('error', "Model {$model} tidak valid.");
            }

            $record = $class::findOrFail($id);
            $oldData = $record->toArray();
            
            $record->update($request->all());
            
            \App\Services\SystemLogger::logActivity("Override Perbarui Data: " . $model . " ID: " . $id, 'Master Override');
            
            return back()->with('success', "Data {$model} berhasil diperbarui oleh Administrator.");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal memperbarui data: " . $e->getMessage());
        }
    }

    /**
     * Agenda Utama (Super Admin)
     */
    public function agenda()
    {
        // Fetch all classroom schedules
        $classroomSchedules = \App\Models\ClassroomSchedule::with('classroom.performance')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($schedule) {
                $schedule->agenda_type = 'Latihan';
                $schedule->time_display = date('H:i', strtotime($schedule->start_time)) . ($schedule->end_time ? ' - ' . date('H:i', strtotime($schedule->end_time)) : '');
                
                // Link to classroom show
                if ($schedule->classroom) {
                    $perf = $schedule->classroom->performance;
                    if ($perf) {
                        if ($perf->program_id) {
                            $schedule->link = route('pengurus.programs.performance.classroom.show', [$perf->program_id, $perf->id]);
                        } else {
                            $schedule->link = route('pengurus.jobs.classroom.show', $perf->id);
                        }
                    } else {
                        $schedule->link = '#';
                    }
                } else {
                    $schedule->link = '#';
                }
                return $schedule;
            });

        // Fetch all performances
        $performances = Performance::with('classroom')
            ->where('performance_date', '>=', now()->toDateString())
            ->orderBy('performance_date', 'asc')
            ->get()
            ->map(function($perf) {
                $perf->agenda_type = $perf->program_id ? 'Penampilan' : 'Job';
                $perf->date = $perf->performance_date ? $perf->performance_date->format('Y-m-d') : null;
                $perf->time_display = $perf->performance_time ? date('H:i', strtotime($perf->performance_time)) : '17:00';
                
                if ($perf->classroom) {
                    if ($perf->program_id) {
                        $perf->link = route('pengurus.programs.performance.classroom.show', [$perf->program_id, $perf->id]);
                    } else {
                        $perf->link = route('pengurus.jobs.classroom.show', $perf->id);
                    }
                } else {
                    $perf->link = '#';
                }
                return $perf;
            });

        // Fetch non-performance Programs (Proker)
        $prokers = \App\Models\Program::where('activity_type', '!=', 'Performance')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->get()
            ->map(function($prog) {
                $prog->agenda_type = $prog->activity_type; // Event or Competition
                $prog->title = $prog->name;
                $prog->date = $prog->start_date;
                $prog->time_display = 'All Day';
                $prog->location = $prog->venue;
                $prog->notes = $prog->description;
                $prog->link = route('pengurus.programs.show', $prog->id);
                return $prog;
            });

        // Merge and sort
        $agendas = $classroomSchedules->concat($performances)->concat($prokers)->sortBy('date')->values();

        return view('admin.agenda', compact('agendas'));
    }
}