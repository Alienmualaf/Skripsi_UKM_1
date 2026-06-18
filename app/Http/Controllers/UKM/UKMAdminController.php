<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Performance;
use App\Models\Program;
use App\Models\Inventory;
use App\Models\InventoryLoan;
use App\Models\Material;
use App\Models\Finance;
use App\Models\VoiceClassification;
use App\Models\User;
use App\Models\Role;
use App\Models\Announcement;
use App\Models\NotificationLog;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UKMAdminController extends Controller
{
    /**
     * Admin UKM Dashboard
     */
    public function dashboard()
    {
        $totalMembers = Member::where('status', 'Anggota Aktif')->count();
        $totalPrograms = Program::where('status', 'Berjalan')->count();
        $totalPerformances = Performance::count();
        
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $upcomingAgendas = Performance::where('status', '!=', 'Selesai')
            ->orderBy('performance_date', 'asc')
            ->orderBy('performance_time', 'asc')
            ->take(5)->get();

        $announcements = Announcement::with('creator')->latest()->take(5)->get();

        return view('ukm.dashboard', compact(
            'totalMembers', 'totalPrograms', 'totalPerformances', 'netBalance', 'upcomingAgendas', 'announcements'
        ));
    }

    /**
     * Profil UKM
     */
    public function profile()
    {
        $profile = \App\Models\OrganizationProfile::first();
        if (!$profile) {
            $profile = \App\Models\OrganizationProfile::create([
                'name' => 'Paduan Suara Universitas Pancasila',
                'alias' => 'PSUP',
                'tagline' => 'Satu Suara, Sejuta Harmoni',
            ]);
        }
        $histories = \App\Models\OrganizationHistory::orderBy('year', 'asc')->get();
        return view('ukm.profile.index', compact('profile', 'histories'));
    }

    public function updateProfile(Request $request)
    {
        $profile = \App\Models\OrganizationProfile::firstOrCreate([]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'video_url' => 'nullable|string|max:255',
            
            // Files
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:3072',
            'structure_image' => 'nullable|image|max:4096',
            
            // Recruitment
            'recruitment_start_date' => 'nullable|date',
            'recruitment_end_date' => 'nullable|date',
            'recruitment_requirements' => 'nullable|string',
            'recruitment_stages' => 'nullable|string',

            // Documents
            'company_profile_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'sponsorship_proposal_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'media_kit_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data['recruitment_active'] = $request->has('recruitment_active') ? true : false;

        // File uploads
        if ($request->hasFile('logo')) {
            if ($profile->logo && \Storage::disk('public')->exists($profile->logo)) {
                \Storage::disk('public')->delete($profile->logo);
            }
            $data['logo'] = $request->file('logo')->store('profile', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($profile->banner && \Storage::disk('public')->exists($profile->banner)) {
                \Storage::disk('public')->delete($profile->banner);
            }
            $data['banner'] = $request->file('banner')->store('profile', 'public');
        }

        if ($request->hasFile('structure_image')) {
            if ($profile->structure_image && \Storage::disk('public')->exists($profile->structure_image)) {
                \Storage::disk('public')->delete($profile->structure_image);
            }
            $data['structure_image'] = $request->file('structure_image')->store('profile', 'public');
        }

        if ($request->hasFile('company_profile_pdf')) {
            if ($profile->company_profile_pdf && \Storage::disk('public')->exists($profile->company_profile_pdf)) {
                \Storage::disk('public')->delete($profile->company_profile_pdf);
            }
            $data['company_profile_pdf'] = $request->file('company_profile_pdf')->store('profile/docs', 'public');
        }

        if ($request->hasFile('sponsorship_proposal_pdf')) {
            if ($profile->sponsorship_proposal_pdf && \Storage::disk('public')->exists($profile->sponsorship_proposal_pdf)) {
                \Storage::disk('public')->delete($profile->sponsorship_proposal_pdf);
            }
            $data['sponsorship_proposal_pdf'] = $request->file('sponsorship_proposal_pdf')->store('profile/docs', 'public');
        }

        if ($request->hasFile('media_kit_pdf')) {
            if ($profile->media_kit_pdf && \Storage::disk('public')->exists($profile->media_kit_pdf)) {
                \Storage::disk('public')->delete($profile->media_kit_pdf);
            }
            $data['media_kit_pdf'] = $request->file('media_kit_pdf')->store('profile/docs', 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Profil UKM PSUP berhasil diperbarui.');
    }

    public function deleteStructureImage()
    {
        $profile = \App\Models\OrganizationProfile::first();
        if ($profile && $profile->structure_image) {
            if (\Storage::disk('public')->exists($profile->structure_image)) {
                \Storage::disk('public')->delete($profile->structure_image);
            }
            $profile->update(['structure_image' => null]);
        }
        return back()->with('success', 'Gambar struktur organisasi berhasil dihapus.');
    }

    public function historyStore(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|string|max:4',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('history', 'public');
        }

        \App\Models\OrganizationHistory::create($data);

        return back()->with('success', 'Sejarah baru berhasil ditambahkan.');
    }

    public function historyUpdate(Request $request, $id)
    {
        $history = \App\Models\OrganizationHistory::findOrFail($id);
        
        $data = $request->validate([
            'year' => 'required|string|max:4',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($history->photo && \Storage::disk('public')->exists($history->photo)) {
                \Storage::disk('public')->delete($history->photo);
            }
            $data['photo'] = $request->file('photo')->store('history', 'public');
        }

        $history->update($data);

        return back()->with('success', 'Data sejarah berhasil diperbarui.');
    }

    public function historyDestroy($id)
    {
        $history = \App\Models\OrganizationHistory::findOrFail($id);
        if ($history->photo && \Storage::disk('public')->exists($history->photo)) {
            \Storage::disk('public')->delete($history->photo);
        }
        $history->delete();

        return back()->with('success', 'Sejarah berhasil dihapus.');
    }

    /**
     * Anggota Management
     */
    public function members(Request $request)
    {
        $search = $request->input('search');
        $voice = $request->input('voice');
        $status = $request->input('status');
        
        $query = Member::with(['voiceClassification', 'user']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%");
            });
        }

        if ($voice) {
            $query->where('voice_classification_id', $voice);
        }

        if ($status) {
            $dbStatus = ($status === 'active') ? 'Anggota Aktif' : 'Alumni';
            $query->where('status', $dbStatus);
        }

        $members = $query->latest()->paginate(15)->withQueryString();
        $classifications = VoiceClassification::all();

        return view('ukm.members.index', compact('members', 'search', 'classifications'));
    }

    public function editMember($id)
    {
        $member = Member::findOrFail($id);
        $classifications = VoiceClassification::all();
        return view('ukm.members.edit', compact('member', 'classifications'));
    }

    public function updateMember(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'npm' => 'required|string|max:50|unique:members,npm,'.$id,
            'gender' => 'sometimes|required|in:L,P',
            'faculty' => 'required|string|max:100',
            'major' => 'sometimes|required|string|max:100',
            'class_year' => 'sometimes|required|string|max:4',
            'birth_place' => 'sometimes|required|string|max:100',
            'birth_date' => 'sometimes|required|date',
            'address' => 'sometimes|required|string',
            'phone' => 'required|string|max:20',
            'email' => 'sometimes|required|email|unique:members,email,'.$id,
            'status' => 'required|in:Calon Anggota,Anggota Aktif,Alumni,Nonaktif',
            'voice_classification_id' => 'nullable|exists:voice_classifications,id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 'public');
            $data['photo'] = $path;
        }

        if ($member->user && $member->user->role && $member->user->role->name !== 'anggota') {
            $data['voice_classification_id'] = null;
        }

        $member->update($data);

        // Also update corresponding user email/name if they exist and are provided
        if ($member->user) {
            $userData = [];
            if ($request->has('name')) {
                $userData['name'] = $request->name;
            }
            if ($request->has('email')) {
                $userData['email'] = $request->email;
            }
            if (!empty($userData)) {
                $member->user->update($userData);
            }
        }

        return redirect()->route('ukm.members')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function deleteMember($id)
    {
        $member = Member::findOrFail($id);
        if ($member->user) {
            $member->user->delete();
        }
        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    /**
     * Registrasi / Calon Anggota Recruitment
     */
    /**
     * Registrasi / Calon Anggota Recruitment
     */
    public function registrations()
    {
        $registrations = Member::where('status', 'Calon Anggota')->latest()->paginate(15);
        $voiceClassifications = VoiceClassification::all();
        return view('ukm.registrations.index', compact('registrations', 'voiceClassifications'));
    }

    public function verifyRegistration(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $action = $request->input('action'); // Terima, Tolak
        $user = $member->user;

        if ($action === 'Terima') {
            $voiceClassId = $request->input('voice_classification_id');
            $voiceClass = VoiceClassification::find($voiceClassId);
            $voiceClassName = $voiceClass ? $voiceClass->name : 'Sopran';

            // Generate temporary password
            $tempPassword = 'PSUP-' . strtoupper(\Illuminate\Support\Str::random(6));

            // Update user status & password
            if ($user) {
                $user->update([
                    'password' => Hash::make($tempPassword),
                    'status' => 'active',
                ]);
            }

            // Update member status & voice classification
            $member->update([
                'status' => 'Anggota Aktif',
                'voice_classification_id' => $voiceClassId,
            ]);

            // 1. Send Email Notification
            $emailData = [
                'name' => $member->name,
                'voice_classification' => $voiceClassName,
                'email' => $member->email,
                'password' => $tempPassword,
                'login_url' => url('/login'),
            ];
            $subject = 'Selamat bergabung di Paduan Suara Universitas Pancasila!';
            NotificationService::sendEmail($user?->id, $member->email, $member->name, $subject, 'emails.accepted', $emailData);

            // 2. Send WhatsApp Notification
            $waMessage = "Halo {$member->name}, Selamat bergabung di Paduan Suara Universitas Pancasila (PSUP)! Anda diterima sebagai anggota dengan klasifikasi suara: {$voiceClassName}. Silakan login ke sistem menggunakan email: {$member->email} dan password sementara: {$tempPassword} di " . url('/login') . ". Harap segera lengkapi profil Anda setelah login pertama. Terima kasih.";
            NotificationService::sendWhatsApp($user?->id, $member->phone, $member->name, $waMessage);

            return back()->with('success', "Pendaftaran {$member->name} diterima. Akun anggota aktif & notifikasi (Email + WhatsApp) dikirim.");
        } else {
            // Update user status
            if ($user) {
                $user->update([
                    'status' => 'inactive',
                ]);
            }

            // Update member status
            $member->update([
                'status' => 'Ditolak',
            ]);

            // 1. Send Email Notification
            $emailData = [
                'name' => $member->name,
            ];
            $subject = 'Pemberitahuan Hasil Pendaftaran - Paduan Suara Universitas Pancasila';
            NotificationService::sendEmail($user?->id, $member->email, $member->name, $subject, 'emails.rejected', $emailData);

            // 2. Send WhatsApp Notification
            $waMessage = "Halo {$member->name}, Terima kasih atas ketertarikan Anda untuk bergabung dengan PSUP. Setelah melalui proses evaluasi berkas dan klasifikasi suara, dengan menyesal kami informasikan bahwa pendaftaran Anda belum dapat kami terima untuk periode ini. Tetap semangat dan silakan mendaftar kembali di rekrutmen berikutnya!";
            NotificationService::sendWhatsApp($user?->id, $member->phone, $member->name, $waMessage);

            return back()->with('success', "Pendaftaran {$member->name} ditolak. Notifikasi penolakan (Email + WhatsApp) dikirim.");
        }
    }

    public function emailLogs()
    {
        $logs = NotificationLog::latest()->paginate(15);
        return view('ukm.email_logs.index', compact('logs'));
    }

    public function resendEmail($id)
    {
        $log = NotificationLog::findOrFail($id);

        if ($log->type === 'email') {
            try {
                Mail::html($log->content, function($message) use ($log) {
                    $message->to($log->recipient, $log->recipient_name)
                            ->subject($log->subject);
                });

                $log->update([
                    'status' => 'success',
                    'error_message' => null,
                ]);

                return back()->with('success', 'Email berhasil dikirim ulang.');
            } catch (\Exception $e) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                return back()->with('error', 'Gagal mengirim ulang email: ' . $e->getMessage());
            }
        } else {
            // WhatsApp
            $token = env('FONNTE_TOKEN');
            $formattedPhone = $log->recipient;
            if (substr($formattedPhone, 0, 1) === '0') {
                $formattedPhone = '62' . substr($formattedPhone, 1);
            }

            try {
                if (empty($token) || $token === 'your_token_here') {
                    $log->update([
                        'status' => 'success',
                        'error_message' => 'Simulated success (FONNTE_TOKEN not configured in .env)',
                    ]);
                    return back()->with('success', 'WhatsApp (Simulated) berhasil dikirim ulang.');
                }

                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $log->content,
                ]);

                $result = $response->json();
                $status = (isset($result['status']) && $result['status'] == true) ? 'success' : 'failed';
                $err = $status === 'failed' ? ($result['reason'] ?? 'Unknown Fonnte error') : null;

                $log->update([
                    'status' => $status,
                    'error_message' => $err,
                ]);

                if ($status === 'success') {
                    return back()->with('success', 'WhatsApp berhasil dikirim ulang.');
                } else {
                    return back()->with('error', 'Gagal mengirim ulang WhatsApp: ' . $err);
                }
            } catch (\Exception $e) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                return back()->with('error', 'Gagal mengirim ulang WhatsApp: ' . $e->getMessage());
            }
        }
    }

    /**
     * Pelatih (Trainers) CRUD
     */
    public function index()
    {
        $trainers = Trainer::latest()->paginate(10);
        return view('ukm.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('ukm.trainers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'photo' => 'nullable|image|max:2048',
        ]);

        if (!isset($data['salary'])) {
            $data['salary'] = 0;
        }
        if (!isset($data['status'])) {
            $data['status'] = 'Aktif';
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('trainers', 'public');
            $data['photo'] = $path;
        }

        Trainer::create($data);

        return redirect()->route('ukm.trainers.index')->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $trainer = Trainer::findOrFail($id);
        return view('ukm.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Aktif,Nonaktif',
            'photo' => 'nullable|image|max:2048',
        ]);

        if (!isset($data['salary'])) {
            unset($data['salary']);
        }
        if (!isset($data['status'])) {
            unset($data['status']);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('trainers', 'public');
            $data['photo'] = $path;
        }

        $trainer->update($data);

        return redirect()->route('ukm.trainers.index')->with('success', 'Data pelatih berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();
        return back()->with('success', 'Pelatih berhasil dihapus.');
    }

    /**
     * Klasifikasi Suara
     */
    public function voiceClassifications()
    {
        $classifications = VoiceClassification::withCount('members')->get();
        return view('ukm.voice-classifications.index', compact('classifications'));
    }

    public function storeVoiceClassification(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:voice_classifications,name',
            'description' => 'nullable|string',
        ]);

        VoiceClassification::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Klasifikasi suara berhasil ditambahkan.');
    }

    public function deleteVoiceClassification($id)
    {
        $vc = VoiceClassification::findOrFail($id);
        $vc->delete();
        return back()->with('success', 'Klasifikasi suara berhasil dihapus.');
    }

    /**
     * Laporan (Reporting Screen)
     */
    public function reports(Request $request)
    {
        $type = $request->input('type', 'keuangan'); // keuangan, kegiatan, rekrutmen, lpj, absensi, inventaris
        $start_date = $request->input('start_date', date('Y-m-01'));
        $end_date = $request->input('end_date', date('Y-m-d'));

        $data = [];

        if ($type === 'keuangan') {
            $data = Finance::with('category')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'asc')
                ->get();
        } elseif ($type === 'kegiatan') {
            $data = Performance::whereBetween('performance_date', [$start_date, $end_date])
                ->orderBy('performance_date', 'asc')
                ->get();
        } elseif ($type === 'rekrutmen') {
            $data = Member::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
                ->get();
        } elseif ($type === 'lpj') {
            $data = Program::whereBetween('start_date', [$start_date, $end_date])
                ->get();
        } elseif ($type === 'absensi') {
            $data = \App\Models\Attendance::withCount(['details as total_hadir' => function($q) {
                $q->where('status', 'Hadir');
            }])->whereBetween('date', [$start_date, $end_date])->get();
        } elseif ($type === 'inventaris') {
            $data = Inventory::withCount(['loans as total_loans' => function($q) {
                $q->where('status', 'Dipinjam');
            }])->get();
        }

        $agendas = Performance::orderBy('performance_date', 'desc')->get();

        return view('ukm.reports.index', compact('type', 'start_date', 'end_date', 'data', 'agendas'));
    }

    public function exportReport(Request $request)
    {
        $rawType = $request->input('type', 'keuangan');
        
        // Normalize report types
        if ($rawType === 'keuangan' || $rawType === 'finances') {
            $type = 'keuangan';
        } elseif ($rawType === 'kegiatan' || $rawType === 'events') {
            $type = 'kegiatan';
        } elseif ($rawType === 'rekrutmen' || $rawType === 'memberships') {
            $type = 'rekrutmen';
        } elseif ($rawType === 'lpj') {
            $type = 'lpj';
        } elseif ($rawType === 'absensi') {
            $type = 'absensi';
        } elseif ($rawType === 'inventaris') {
            $type = 'inventaris';
        } else {
            $type = $rawType;
        }

        $start_date = $request->input('start_date', date('Y-m-01'));
        $end_date = $request->input('end_date', date('Y-m-d'));

        $data = [];
        if ($type === 'keuangan') {
            $data = Finance::with('category')
                ->whereBetween('transaction_date', [$start_date, $end_date])
                ->orderBy('transaction_date', 'asc')
                ->get();
        } elseif ($type === 'kegiatan') {
            $data = Performance::whereBetween('performance_date', [$start_date, $end_date])->get();
        } elseif ($type === 'rekrutmen') {
            $data = Member::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->get();
        } elseif ($type === 'lpj') {
            $data = Program::whereBetween('start_date', [$start_date, $end_date])->get();
        } elseif ($type === 'absensi') {
            $data = \App\Models\Attendance::withCount(['details as total_hadir' => function($q) {
                $q->where('status', 'Hadir');
            }])->whereBetween('date', [$start_date, $end_date])->get();
        } elseif ($type === 'inventaris') {
            $data = Inventory::all();
        }

        return view('ukm.reports.print', compact('type', 'start_date', 'end_date', 'data'));
    }
}
