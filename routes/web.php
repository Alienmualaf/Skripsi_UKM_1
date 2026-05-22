<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

// AUTH
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UKMController;
use App\Http\Controllers\Admin\GlobalDataController;

// BIRO
// removed

// UKM
use App\Http\Controllers\UKM\DashboardController as UKMDashboard;
use App\Http\Controllers\UKM\MemberController;
use App\Http\Controllers\UKM\EventController;
use App\Http\Controllers\UKM\FinanceController;
use App\Http\Controllers\UKM\InventoryController;

// MEMBER
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\JoinUKMController;

// SHARED
use App\Http\Controllers\Shared\ProfileController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::view('/', 'public.landing');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login']);

// REGISTER
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROFILE (SEMUA ROLE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/calendar', [App\Http\Controllers\Shared\CalendarController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword']);
});


/*
|--------------------------------------------------------------------------
| ADMIN (SUPER ADMIN)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index']);

    Route::resource('/users', UserController::class);

    Route::resource('/ukm', UKMController::class);

    // GLOBAL DATA ACCESS & MANAGEMENT
    Route::get('/events', [GlobalDataController::class, 'events']);
    Route::put('/events/{event}', [GlobalDataController::class, 'updateEvent']);
    Route::delete('/events/{event}', [GlobalDataController::class, 'destroyEvent']);
    
    Route::get('/finances', [GlobalDataController::class, 'finances']);
    Route::put('/finances/{finance}', [GlobalDataController::class, 'updateFinance']);
    Route::delete('/finances/{finance}', [GlobalDataController::class, 'destroyFinance']);
    
    Route::get('/inventories', [GlobalDataController::class, 'inventories']);
    Route::put('/inventories/{inventory}', [GlobalDataController::class, 'updateInventory']);
    Route::delete('/inventories/{inventory}', [GlobalDataController::class, 'destroyInventory']);
    
    Route::get('/galleries', [GlobalDataController::class, 'galleries']);
    Route::put('/galleries/{gallery}', [GlobalDataController::class, 'updateGallery']);
    Route::delete('/galleries/{gallery}', [GlobalDataController::class, 'destroyGallery']);
    
    Route::get('/materials', [GlobalDataController::class, 'materials']);
    Route::put('/materials/{material}', [GlobalDataController::class, 'updateMaterial']);
    Route::delete('/materials/{material}', [GlobalDataController::class, 'destroyMaterial']);

    Route::get('/announcements', [GlobalDataController::class, 'announcements']);
    Route::put('/announcements/{announcement}', [GlobalDataController::class, 'updateAnnouncement']);
    Route::delete('/announcements/{announcement}', [GlobalDataController::class, 'destroyAnnouncement']);

    Route::get('/memberships', [GlobalDataController::class, 'memberships']);
    Route::put('/memberships/{membership}', [GlobalDataController::class, 'updateMembership']);
    Route::delete('/memberships/{membership}', [GlobalDataController::class, 'destroyMembership']);

    Route::get('/coaches', [GlobalDataController::class, 'coaches']);
    Route::put('/coaches/{coach}', [GlobalDataController::class, 'updateCoach']);
    Route::delete('/coaches/{coach}', [GlobalDataController::class, 'destroyCoach']);

    Route::get('/attendances', [GlobalDataController::class, 'attendances']);
    Route::put('/attendances/{attendance}', [GlobalDataController::class, 'updateAttendance']);
    Route::delete('/attendances/{attendance}', [GlobalDataController::class, 'destroyAttendance']);

    Route::get('/coach-attendances', [GlobalDataController::class, 'coachAttendances']);
    Route::put('/coach-attendances/{coachAttendance}', [GlobalDataController::class, 'updateCoachAttendance']);
    Route::delete('/coach-attendances/{coachAttendance}', [GlobalDataController::class, 'destroyCoachAttendance']);

    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index']);
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update']);
    Route::post('/settings/backup', [App\Http\Controllers\Admin\SettingsController::class, 'backup']);
    Route::post('/settings/restore', [App\Http\Controllers\Admin\SettingsController::class, 'restore']);
});


/*
|--------------------------------------------------------------------------
| BIRO KEMAHASISWAAN (REMOVED)
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| UKM (ADMIN UKM)
|--------------------------------------------------------------------------
*/

Route::prefix('ukm')->middleware(['auth', 'admin_ukm'])->group(function () {

    Route::get('/dashboard', [UKMDashboard::class, 'index']);

    // MEMBER MANAGEMENT
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/members/{id}/approve', [MemberController::class, 'approve']);
    Route::put('/members/{id}/classification', [MemberController::class, 'updateClassification']);
    Route::delete('/members/{id}', [MemberController::class, 'destroy']);
    Route::put('/members/{id}/role', [MemberController::class, 'updateRole']);

    // PROFIL UKM
    Route::get('/profile', [App\Http\Controllers\UKM\ProfileController::class, 'index']);
    Route::put('/profile', [App\Http\Controllers\UKM\ProfileController::class, 'update']);
    Route::post('/profile/classifications', [App\Http\Controllers\UKM\ProfileController::class, 'addClassification']);
    Route::delete('/profile/classifications/{id}', [App\Http\Controllers\UKM\ProfileController::class, 'deleteClassification']);

    // COACH MANAGEMENT
    Route::resource('/coaches', App\Http\Controllers\UKM\CoachController::class);

    // LMS - ANNOUNCEMENTS
    Route::resource('/announcements', App\Http\Controllers\UKM\AnnouncementController::class);

    // COACH ATTENDANCES
    Route::get('/coach-attendances', [App\Http\Controllers\UKM\CoachAttendanceController::class, 'index'])->name('coach-attendances.index');
    Route::get('/coach-attendances/event/{event_id}', [App\Http\Controllers\UKM\CoachAttendanceController::class, 'sessions'])->name('coach-attendances.sessions');
    Route::get('/coach-attendances/session/{session_id}', [App\Http\Controllers\UKM\CoachAttendanceController::class, 'sessionShow'])->name('coach-attendances.session-show');
    Route::post('/coach-attendances/session/{session_id}', [App\Http\Controllers\UKM\CoachAttendanceController::class, 'sessionStore'])->name('coach-attendances.session-store');
    Route::delete('/coach-attendances/{id}', [App\Http\Controllers\UKM\CoachAttendanceController::class, 'destroy'])->name('coach-attendances.destroy');

    // EVENT
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::get('/events/{id}/participants', [EventController::class, 'showParticipants']);
    Route::post('/events/{id}/participants', [EventController::class, 'updateParticipants']);
    Route::post('/events/{id}/coaches', [EventController::class, 'updateCoaches']);


    // FINANCE
    Route::get('/finance/all', [FinanceController::class, 'all']);
    Route::get('/finance', [FinanceController::class, 'index']);
    Route::post('/finance', [FinanceController::class, 'store']);
    Route::delete('/finance/{id}', [FinanceController::class, 'destroy']);


    // INVENTORY
    Route::get('/inventory/all', [InventoryController::class, 'all']);
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);

    // CLASSROOM ADMIN
    Route::get('/classroom', [App\Http\Controllers\UKM\ClassroomAdminController::class, 'index'])->name('ukm.classroom');
    Route::get('/classroom/{event_id}', [App\Http\Controllers\UKM\ClassroomAdminController::class, 'classroom'])->name('ukm.classroom.show');

    // MATERIALS (LMS)
    Route::get('/materials', [App\Http\Controllers\UKM\MaterialController::class, 'index']);
    Route::post('/materials', [App\Http\Controllers\UKM\MaterialController::class, 'store']);
    Route::delete('/materials/{id}', [App\Http\Controllers\UKM\MaterialController::class, 'destroy']);

    // GALLERIES
    Route::get('/galleries', [App\Http\Controllers\UKM\GalleryController::class, 'index']);
    Route::post('/galleries', [App\Http\Controllers\UKM\GalleryController::class, 'store']);
    Route::delete('/galleries/{id}', [App\Http\Controllers\UKM\GalleryController::class, 'destroy']);

    // ATTENDANCES
    Route::get('/attendances', [App\Http\Controllers\UKM\AttendanceController::class, 'index']);
    Route::get('/attendances/{event_id}', [App\Http\Controllers\UKM\AttendanceController::class, 'show']);
    Route::post('/attendances/{event_id}', [App\Http\Controllers\UKM\AttendanceController::class, 'store']);

    // REKAP ABSENSI
    Route::get('/events/{id}/rekap/members', [App\Http\Controllers\UKM\AttendanceReportController::class, 'memberRekap'])->name('ukm.rekap.members');
    Route::get('/events/{id}/rekap/coaches', [App\Http\Controllers\UKM\AttendanceReportController::class, 'coachRekap'])->name('ukm.rekap.coaches');

    // REPORTS
    Route::get('/reports', [App\Http\Controllers\UKM\ReportController::class, 'index']);
    Route::post('/reports/export', [App\Http\Controllers\UKM\ReportController::class, 'export']);

    // ACTIVITY SESSIONS (PERTEMUAN)
    Route::post('/events/{id}/sessions', [App\Http\Controllers\UKM\ActivitySessionController::class, 'store']);
    Route::delete('/sessions/{id}', [App\Http\Controllers\UKM\ActivitySessionController::class, 'destroy']);
    Route::get('/sessions/{id}/attendance', [App\Http\Controllers\UKM\ActivitySessionController::class, 'showAttendance'])->name('ukm.sessions.attendance');
    Route::post('/sessions/{id}/attendance', [App\Http\Controllers\UKM\ActivitySessionController::class, 'storeAttendance']);
    Route::post('/sessions/{id}/attendance/toggle', [App\Http\Controllers\UKM\ActivitySessionController::class, 'toggleAttendance'])->name('ukm.sessions.attendance.toggle');
});


/*
|--------------------------------------------------------------------------
| MEMBER (ANGGOTA)
|--------------------------------------------------------------------------
|*/

Route::prefix('member')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [MemberDashboard::class, 'index']);
    Route::get('/profile', [App\Http\Controllers\Member\ProfileController::class, 'index']);
    Route::put('/profile', [App\Http\Controllers\Member\ProfileController::class, 'update']);

    Route::get('/join', [JoinUKMController::class, 'index']);
    Route::post('/join', [JoinUKMController::class, 'join']);
    Route::get('/ukm/{id}', [JoinUKMController::class, 'show']);

    // MEMBER ATTENDANCE (SELF CHECK-IN)
    Route::get('/sessions/{id}/attendance', [App\Http\Controllers\UKM\ActivitySessionController::class, 'showAttendance'])->name('member.sessions.attendance');
    Route::post('/sessions/{id}/attendance', [App\Http\Controllers\UKM\ActivitySessionController::class, 'storeAttendance']);
});


/*
|--------------------------------------------------------------------------
| ROOM UKM (HARUS MEMBER APPROVED)
|--------------------------------------------------------------------------
|*/

Route::middleware(['auth', 'membership'])->group(function () {

    Route::get('/room/{ukm}', function (\App\Models\UKM $ukm) {
        return redirect('/room/' . $ukm->id . '/classroom');
    })->name('ukm.room');

    // CLASSROOM
    Route::get('/room/{ukm}/classroom', [App\Http\Controllers\Member\ClassroomController::class, 'index']);
    Route::get('/room/{ukm}/classroom/{event}', [App\Http\Controllers\Member\ClassroomController::class, 'classroom'])->name('classroom.show');
    Route::get('/room/{ukm}/materials/{id}', [App\Http\Controllers\Member\ClassroomController::class, 'downloadMaterial']);

});