<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public Controller
use App\Http\Controllers\PublicController;

// Admin (Super Admin) Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;

// Admin UKM Controllers
use App\Http\Controllers\UKM\UKMAdminController;
use App\Http\Controllers\UKM\AnnouncementController;

// Pengurus Controllers
use App\Http\Controllers\UKM\DashboardController as UKMDashboard;
use App\Http\Controllers\UKM\ProgramController;
use App\Http\Controllers\UKM\TrainerController;
use App\Http\Controllers\UKM\JobController;
use App\Http\Controllers\UKM\AttendanceController;
use App\Http\Controllers\UKM\InventoryController;
use App\Http\Controllers\UKM\FinanceController;
use App\Http\Controllers\UKM\LetterController;
use App\Http\Controllers\UKM\MaterialController;
use App\Http\Controllers\UKM\PerformanceController;
use App\Http\Controllers\UKM\ClassroomController;
use App\Http\Controllers\UKM\ProgramReportController;
use App\Http\Controllers\UKM\ReportController;
use App\Http\Controllers\UKM\GalleryController;
use App\Http\Controllers\UKM\AchievementController;

// Member Controllers
use App\Http\Controllers\Member\MemberController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang', [PublicController::class, 'about'])->name('about');
Route::get('/sejarah', [PublicController::class, 'history'])->name('history');
Route::get('/visi-misi', [PublicController::class, 'visionMission'])->name('vision-mission');
Route::get('/struktur', [PublicController::class, 'structure'])->name('structure');
Route::get('/pelatih', [PublicController::class, 'trainers'])->name('trainers');
Route::get('/prestasi', [PublicController::class, 'achievements'])->name('achievements');
Route::get('/agenda', [PublicController::class, 'agendas'])->name('agendas');
Route::get('/galeri', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/daftar', [PublicController::class, 'showRegisterForm'])->name('register-candidate');
Route::post('/daftar', [PublicController::class, 'submitRegisterForm'])->name('register-candidate.submit');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::redirect('/register', '/daftar');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Administrator Routes (Role: administrator)
|--------------------------------------------------------------------------
*/
Route::redirect('/admin', '/admin/dashboard');
Route::prefix('admin')->middleware(['auth', 'role:administrator'])->name('admin.')->group(function () {
    Route::get('/', function() { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', AdminUser::class);
    Route::post('/users/{id}/reset-password', [AdminUser::class, 'forceResetPassword'])->name('users.reset-password');
    Route::post('/users/{id}/toggle-status', [AdminUser::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Roles & Permissions
    Route::get('/roles-permissions', [AdminDashboard::class, 'rolesPermissions'])->name('roles-permissions');
    Route::post('/roles', [AdminDashboard::class, 'storeRole'])->name('roles.store');
    Route::delete('/roles/{id}', [AdminDashboard::class, 'destroyRole'])->name('roles.destroy');
    
    // Backup & Restore
    Route::post('/backup', [AdminDashboard::class, 'backup'])->name('backup');
    Route::post('/restore', [AdminDashboard::class, 'restore'])->name('restore');
    Route::delete('/backup/{filename}', [AdminDashboard::class, 'deleteBackup'])->name('backup.destroy');
    
    // Website Settings
    Route::get('/settings', [AdminDashboard::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminDashboard::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/email-test', [AdminDashboard::class, 'testEmail'])->name('settings.email-test');
    
    // Monitoring Sistem (Logs, Login History, Audit Trail)
    Route::get('/logs/activity', [AdminDashboard::class, 'activityLogs'])->name('logs.activity');
    Route::get('/logs/login', [AdminDashboard::class, 'loginHistory'])->name('logs.login');
    Route::get('/logs/audit', [AdminDashboard::class, 'auditTrail'])->name('logs.audit');
    
    // Monitoring Data Organisasi
    Route::get('/monitor/members', [AdminDashboard::class, 'monitorMembers'])->name('monitor.members');
    Route::get('/monitor/agendas', [AdminDashboard::class, 'monitorAgendas'])->name('monitor.agendas');
    Route::get('/monitor/keuangan', [AdminDashboard::class, 'monitorKeuangan'])->name('monitor.keuangan');
    Route::get('/monitor/inventaris', [AdminDashboard::class, 'monitorInventaris'])->name('monitor.inventaris');
    Route::get('/monitor/jobs', [AdminDashboard::class, 'monitorJobs'])->name('monitor.jobs');
    
    // Override Actions
    Route::post('/monitor/override/{model}/{id}', [AdminDashboard::class, 'overrideUpdate'])->name('monitor.override.update');
    Route::delete('/monitor/override/{model}/{id}', [AdminDashboard::class, 'overrideDelete'])->name('monitor.override.delete');

    // Maintenance Cache
    Route::post('/maintenance/clear-cache', [AdminDashboard::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::get('/maintenance/system-log', [AdminDashboard::class, 'systemLog'])->name('maintenance.system-log');
});

/*
|--------------------------------------------------------------------------
| Admin UKM Routes (Role: admin_ukm)
|--------------------------------------------------------------------------
*/
Route::redirect('/ukm', '/ukm/dashboard');
Route::prefix('ukm')->middleware(['auth', 'role:admin_ukm,administrator'])->name('ukm.')->group(function () {
    Route::get('/', function() { return redirect()->route('ukm.dashboard'); });
    Route::get('/dashboard', [UKMAdminController::class, 'dashboard'])->name('dashboard');
    
    // Profil UKM
    Route::get('/profile', [UKMAdminController::class, 'profile'])->name('profile');
    Route::match(['POST', 'PUT'], '/profile', [UKMAdminController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/structure-image', [UKMAdminController::class, 'deleteStructureImage'])->name('profile.structure-image.destroy');
    
    // Sejarah Organisasi
    Route::post('/profile/history', [UKMAdminController::class, 'historyStore'])->name('profile.history.store');
    Route::match(['POST', 'PUT'], '/profile/history/{id}', [UKMAdminController::class, 'historyUpdate'])->name('profile.history.update');
    Route::delete('/profile/history/{id}', [UKMAdminController::class, 'historyDestroy'])->name('profile.history.destroy');
    
    // Registrasi Anggota (Recruitment)
    Route::get('/registrations', [UKMAdminController::class, 'registrations'])->name('registrations');
    Route::post('/registrations/{id}/verify', [UKMAdminController::class, 'verifyRegistration'])->name('registrations.verify');
    
    // Pelatih — Admin UKM hanya bisa lihat & edit (tidak bisa tambah/hapus)
    Route::get('/trainers', [UKMAdminController::class, 'index'])->name('trainers.index');
    Route::get('/trainers/{id}/edit', [UKMAdminController::class, 'edit'])->name('trainers.edit');
    Route::match(['POST','PUT'], '/trainers/{id}', [UKMAdminController::class, 'update'])->name('trainers.update');
    
    // Klasifikasi Suara
    Route::get('/voice-classifications', [UKMAdminController::class, 'voiceClassifications'])->name('voice-classifications');
    Route::post('/voice-classifications', [UKMAdminController::class, 'storeVoiceClassification'])->name('voice-classifications.store');
    Route::delete('/voice-classifications/{id}', [UKMAdminController::class, 'deleteVoiceClassification'])->name('voice-classifications.destroy');
});

Route::prefix('ukm')->middleware(['auth', 'role:admin_ukm,administrator,pengurus'])->name('ukm.')->group(function () {
    // Anggota
    Route::get('/members', [UKMAdminController::class, 'members'])->name('members');
    Route::get('/members/{id}/edit', [UKMAdminController::class, 'editMember'])->name('members.edit');
    Route::match(['POST', 'PUT'], '/members/{id}', [UKMAdminController::class, 'updateMember'])->name('members.update');
    Route::delete('/members/{id}', [UKMAdminController::class, 'deleteMember'])->name('members.destroy');
    
    // Laporan (4 jenis)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    // Laporan Kegiatan
    Route::get('/reports/kegiatan/{program}', [ReportController::class, 'showKegiatan'])->name('reports.kegiatan');
    Route::post('/reports/kegiatan/{program}', [ReportController::class, 'storeKegiatan'])->name('reports.kegiatan.store');
    Route::delete('/reports/kegiatan/{program}', [ReportController::class, 'destroyKegiatan'])->name('reports.kegiatan.destroy');
    Route::get('/reports/kegiatan/{program}/print', [ReportController::class, 'printKegiatan'])->name('reports.kegiatan.print');
    // Laporan Rekrutmen
    Route::get('/reports/rekrutmen', [ReportController::class, 'rekrutmen'])->name('reports.rekrutmen');
    Route::get('/reports/rekrutmen/print', [ReportController::class, 'printRekrutmen'])->name('reports.rekrutmen.print');
    // Laporan Keuangan
    Route::get('/reports/keuangan', [ReportController::class, 'keuangan'])->name('reports.keuangan');
    Route::get('/reports/keuangan/print', [ReportController::class, 'printKeuangan'])->name('reports.keuangan.print');
    // LPJ
    Route::get('/reports/lpj', [ReportController::class, 'lpj'])->name('reports.lpj');
    Route::get('/reports/lpj/print', [ReportController::class, 'printLpj'])->name('reports.lpj.print');
    // Legacy (old) export
    Route::match(['GET', 'POST'], '/reports/export', [UKMAdminController::class, 'exportReport'])->name('reports.export');

    // Galeri
    Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::post('/galleries/{id}/toggle-landing', [GalleryController::class, 'toggleLanding'])->name('galleries.toggle-landing');
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    // Prestasi
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::post('/achievements', [AchievementController::class, 'store'])->name('achievements.store');
    Route::put('/achievements/{id}', [AchievementController::class, 'update'])->name('achievements.update');
    Route::post('/achievements/{id}/toggle-landing', [AchievementController::class, 'toggleLanding'])->name('achievements.toggle-landing');
    Route::delete('/achievements/{id}', [AchievementController::class, 'destroy'])->name('achievements.destroy');
});

/*
|--------------------------------------------------------------------------
| Pengurus UKM Routes (Role: pengurus, admin_ukm, administrator)
|--------------------------------------------------------------------------
*/
Route::redirect('/pengurus', '/pengurus/dashboard');
Route::prefix('pengurus')->middleware(['auth', 'role:pengurus,admin_ukm,administrator'])->name('pengurus.')->group(function () {
    Route::get('/', function() { return redirect()->route('pengurus.dashboard'); });
    Route::get('/dashboard', [UKMDashboard::class, 'dashboard'])->name('dashboard');

    // Pelatih — Pengurus bisa tambah, edit, dan hapus
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
    Route::get('/trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
    Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/trainers/{id}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');
    Route::put('/trainers/{id}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::delete('/trainers/{id}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
    
    // Program Kerja — extended with show, report, performance, classroom
    Route::resource('programs', ProgramController::class);
    
    // Pengumuman (Humas)
    Route::resource('announcements', AnnouncementController::class);
    
    // Program Kerja - Laporan (LPJ)
    Route::get('programs/{program}/report', [ProgramReportController::class, 'show'])->name('programs.report');
    Route::post('programs/{program}/report', [ProgramReportController::class, 'store'])->name('programs.report.store');
    Route::delete('programs/{program}/report', [ProgramReportController::class, 'destroy'])->name('programs.report.destroy');
    
    // Program Kerja - Penampilan (hanya tipe Performance)
    Route::get('programs/{program}/performance', [PerformanceController::class, 'show'])->name('programs.performance.show');
    Route::post('programs/{program}/performance', [PerformanceController::class, 'store'])->name('programs.performance.store');
    Route::put('programs/{program}/performance/{performance}', [PerformanceController::class, 'update'])->name('programs.performance.update');
    Route::delete('programs/{program}/performance/{performance}', [PerformanceController::class, 'destroy'])->name('programs.performance.destroy');
    
    // Program Kerja - Classroom (via Performance)
    Route::get('classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('programs/{program}/performance/{performance}/classroom', [ClassroomController::class, 'show'])->name('programs.performance.classroom.show');
    Route::post('programs/{program}/performance/{performance}/classroom/members', [ClassroomController::class, 'syncMembers'])->name('programs.performance.classroom.members');
    Route::post('programs/{program}/performance/{performance}/classroom/materials', [ClassroomController::class, 'addMaterial'])->name('programs.performance.classroom.materials.add');
    Route::delete('programs/{program}/performance/{performance}/classroom/materials/{material}', [ClassroomController::class, 'removeMaterial'])->name('programs.performance.classroom.materials.remove');
    Route::post('programs/{program}/performance/{performance}/classroom/announcements', [ClassroomController::class, 'storeAnnouncement'])->name('programs.performance.classroom.announcements.store');
    Route::delete('programs/{program}/performance/{performance}/classroom/announcements/{announcement}', [ClassroomController::class, 'destroyAnnouncement'])->name('programs.performance.classroom.announcements.destroy');
    Route::post('programs/{program}/performance/{performance}/classroom/schedules', [ClassroomController::class, 'storeSchedule'])->name('programs.performance.classroom.schedules.store');
    Route::delete('programs/{program}/performance/{performance}/classroom/schedules/{schedule}', [ClassroomController::class, 'destroySchedule'])->name('programs.performance.classroom.schedules.destroy');
    Route::post('programs/{program}/performance/{performance}/classroom/songs', [ClassroomController::class, 'storeSongTarget'])->name('programs.performance.classroom.songs.store');
    Route::patch('programs/{program}/performance/{performance}/classroom/songs/{target}', [ClassroomController::class, 'updateSongTarget'])->name('programs.performance.classroom.songs.update');
    Route::delete('programs/{program}/performance/{performance}/classroom/songs/{target}', [ClassroomController::class, 'destroySongTarget'])->name('programs.performance.classroom.songs.destroy');
    
    // Classroom Attendances
    Route::post('programs/{program}/performance/{performance}/classroom/attendances', [ClassroomController::class, 'storeAttendance'])->name('programs.performance.classroom.attendance.store');
    Route::get('programs/{program}/performance/{performance}/classroom/attendances/{attendance}', [ClassroomController::class, 'showAttendance'])->name('programs.performance.classroom.attendance');
    Route::post('programs/{program}/performance/{performance}/classroom/attendances/{attendance}/save', [ClassroomController::class, 'saveAttendance'])->name('programs.performance.classroom.attendance.save');
    
    // Job - Classroom
    Route::get('jobs/{job}/classroom', [ClassroomController::class, 'showJobClassroom'])->name('jobs.classroom.show');
    Route::post('jobs/{job}/classroom/members', [ClassroomController::class, 'syncJobMembers'])->name('jobs.classroom.members');
    Route::post('jobs/{job}/classroom/materials', [ClassroomController::class, 'addJobMaterial'])->name('jobs.classroom.materials.add');
    Route::delete('jobs/{job}/classroom/materials/{material}', [ClassroomController::class, 'removeJobMaterial'])->name('jobs.classroom.materials.remove');
    Route::post('jobs/{job}/classroom/announcements', [ClassroomController::class, 'storeJobAnnouncement'])->name('jobs.classroom.announcements.store');
    Route::delete('jobs/{job}/classroom/announcements/{announcement}', [ClassroomController::class, 'destroyJobAnnouncement'])->name('jobs.classroom.announcements.destroy');
    Route::post('jobs/{job}/classroom/schedules', [ClassroomController::class, 'storeJobSchedule'])->name('jobs.classroom.schedules.store');
    Route::delete('jobs/{job}/classroom/schedules/{schedule}', [ClassroomController::class, 'destroyJobSchedule'])->name('jobs.classroom.schedules.destroy');
    Route::post('jobs/{job}/classroom/songs', [ClassroomController::class, 'storeJobSongTarget'])->name('jobs.classroom.songs.store');
    Route::patch('jobs/{job}/classroom/songs/{target}', [ClassroomController::class, 'updateJobSongTarget'])->name('jobs.classroom.songs.update');
    Route::delete('jobs/{job}/classroom/songs/{target}', [ClassroomController::class, 'destroyJobSongTarget'])->name('jobs.classroom.songs.destroy');
    
    // Job Classroom Attendances
    Route::post('jobs/{job}/classroom/attendances', [ClassroomController::class, 'storeJobAttendance'])->name('jobs.classroom.attendance.store');
    Route::get('jobs/{job}/classroom/attendances/{attendance}', [ClassroomController::class, 'showJobAttendance'])->name('jobs.classroom.attendance');
    Route::post('jobs/{job}/classroom/attendances/{attendance}/save', [ClassroomController::class, 'saveJobAttendance'])->name('jobs.classroom.attendance.save');
    
    // Job & Penampilan (lama, tetap ada)
    Route::resource('jobs', JobController::class);
    Route::post('jobs/{id}/members', [JobController::class, 'assignMembers'])->name('jobs.members');
    

    
    // Keuangan
    Route::resource('finances', FinanceController::class);
    Route::resource('finance-categories', FinanceController::class)->names([
        'index'   => 'finance-categories.index',
        'store'   => 'finance-categories.store',
        'destroy' => 'finance-categories.destroy'
    ]);
    
    // Persuratan
    Route::resource('letters', LetterController::class);
    
    // Inventaris
    Route::get('inventories/loans/all', [InventoryController::class, 'allLoans'])->name('inventories.all_loans');
    Route::resource('inventories', InventoryController::class);
    Route::get('inventories/{id}/loans', [InventoryController::class, 'loans'])->name('inventories.loans');
    Route::post('inventories/{id}/loans', [InventoryController::class, 'storeLoan'])->name('inventories.loans.store');
    Route::post('loans/{loanId}/return', [InventoryController::class, 'returnLoan'])->name('inventories.loans.return');
    
    // Materi Latihan Master (Google Drive Style) - Upload oleh Admin UKM/Pengurus
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/folders', [MaterialController::class, 'storeFolder'])->name('folders.store');
    Route::delete('/folders/{id}', [MaterialController::class, 'deleteFolder'])->name('folders.destroy');
    Route::post('/materials', [MaterialController::class, 'storeMaterial'])->name('materials.store');
    Route::delete('/materials/{id}', [MaterialController::class, 'deleteMaterial'])->name('materials.destroy');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
});

/*
|--------------------------------------------------------------------------
| Member Routes (Role: anggota, pengurus, admin_ukm, administrator)
|--------------------------------------------------------------------------
*/
Route::prefix('member')->middleware(['auth', 'membership'])->name('member.')->group(function () {
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
    Route::match(['POST', 'PUT'], '/profile', [MemberController::class, 'updateProfile'])->name('profile.update');
    
    // Materi Latihan
    Route::get('/materials', [MemberController::class, 'materials'])->name('materials');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
    


    // Classroom Saya
    Route::get('/classrooms', [\App\Http\Controllers\Member\ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classrooms/{classroomId}', [\App\Http\Controllers\Member\ClassroomController::class, 'show'])->name('classrooms.show');
});