<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\Performance;
use App\Models\Gallery;
use App\Models\Achievement;
use App\Models\Registration;
use App\Models\Member;
use App\Models\Program;
use App\Models\OrganizationProfile;
use App\Models\OrganizationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    private function getProfile()
    {
        $profile = OrganizationProfile::first();
        if (!$profile) {
            $profile = OrganizationProfile::create([
                'name' => 'Paduan Suara Universitas Pancasila',
                'alias' => 'PSUP',
                'tagline' => 'Satu Suara, Sejuta Harmoni',
                'description' => 'Mewadahi minat bakat mahasiswa Universitas Pancasila dalam seni olah suara secara profesional, disiplin, berprestasi, dan terorganisir.',
                'vision' => 'Menjadi paduan suara mahasiswa yang unggul, berprestasi di tingkat nasional maupun internasional, serta menjunjung tinggi nilai harmoni dan kekeluargaan.',
                'mission' => "1. Menyelenggarakan latihan olah vokal secara rutin dan terprogram.\n2. Mengikuti berbagai kompetisi paduan suara tingkat nasional maupun internasional.\n3. Berpartisipasi aktif dalam kegiatan internal dan eksternal Universitas Pancasila.\n4. Mempererat tali persaudaraan antar anggota dan alumni PSUP.",
                'address' => 'Gedung UKM Lt. 2 Universitas Pancasila, Srengseng Sawah, Jagakarsa, Jakarta Selatan',
                'email' => 'psup@univpancasila.ac.id',
                'phone' => '081234567890',
                'instagram' => 'https://www.instagram.com/psup_/',
                'youtube' => 'https://www.youtube.com/@paduansuarauniversitaspanc8121',
                'tiktok' => 'https://www.tiktok.com/@_psup',
                'website' => 'http://localhost:8000',
            ]);
        }
        return $profile;
    }

    public function home()
    {
        $profile = $this->getProfile();
        $histories = OrganizationHistory::orderBy('year', 'asc')->get();
        $memberCount = Member::where('status', 'Anggota Aktif')->count();
        $achievementCount = Achievement::count();
        
        // Count performance and program
        $agendaCount = Performance::count();
        $programCount = Program::count();
        
        $trainers = Trainer::where('status', 'Aktif')->take(4)->get();
        
        // Fetch only performances & jobs marked for landing page
        $agendas = Performance::where('show_on_landing', true)->orderBy('performance_date', 'asc')->get();
            
        $galleries = Gallery::where('show_on_landing', true)->latest()->take(6)->get();
        
        // Fetch only events/competitions (non-performance) marked for landing page
        $programs = Program::where('show_on_landing', true)
            ->where('activity_type', '!=', 'Performance')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $achievements = Achievement::where('show_on_landing', true)->orderBy('date', 'desc')->take(3)->get();

        return view('public.landing', compact(
            'profile',
            'histories',
            'memberCount',
            'achievementCount',
            'agendaCount',
            'programCount',
            'trainers',
            'agendas',
            'galleries',
            'programs',
            'achievements'
        ));
    }

    public function about()
    {
        $profile = $this->getProfile();
        return view('public.about', compact('profile'));
    }

    public function history()
    {
        $profile = $this->getProfile();
        $histories = OrganizationHistory::orderBy('year', 'asc')->get();
        return view('public.history', compact('profile', 'histories'));
    }

    public function visionMission()
    {
        $profile = $this->getProfile();
        return view('public.vision_mission', compact('profile'));
    }

    public function structure()
    {
        $profile = $this->getProfile();
        return view('public.structure', compact('profile'));
    }

    public function trainers()
    {
        $profile = $this->getProfile();
        $trainers = Trainer::where('status', 'Aktif')->get();
        return view('public.trainers', compact('profile', 'trainers'));
    }

    public function achievements()
    {
        $profile = $this->getProfile();
        $achievements = Achievement::orderBy('date', 'desc')->get();
        return view('public.achievements', compact('profile', 'achievements'));
    }

    public function agendas()
    {
        $profile = $this->getProfile();
        $agendas = Performance::orderBy('performance_date', 'desc')->get();
        return view('public.agendas', compact('profile', 'agendas'));
    }

    public function gallery()
    {
        $profile = $this->getProfile();
        $galleries = Gallery::latest()->get();
        return view('public.gallery', compact('profile', 'galleries'));
    }

    public function showRegisterForm()
    {
        $profile = $this->getProfile();
        return view('public.register_candidate', compact('profile'));
    }

    public function submitRegisterForm(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:registrations,npm|unique:members,npm',
            'gender' => 'required|in:L,P',
            'faculty' => 'required|string|max:100',
            'major' => 'required|string|max:100',
            'class_year' => 'required|string|max:4',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'choir_experience' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('registrations', 'public');
            $data['photo'] = $path;
        }

        $data['status'] = 'Pending';
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

        Registration::create($data);

        return redirect()->route('register-candidate')->with('success', 'Pendaftaran berhasil dikirim. Tunggu verifikasi admin.');
    }
}
