<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\UKM;
use App\Models\Membership;
use Illuminate\Http\Request;

class JoinUKMController extends Controller
{
    // 🔥 tampilkan list UKM
    public function index()
    {
        $ukms = UKM::all();

        return view('member.join-ukm', compact('ukms'));
    }

    // 🔥 tampilkan profil dan galeri ukm
    public function show($id)
    {
        $ukm = UKM::with(['galleries', 'events'])->findOrFail($id);

        return view('member.ukm-profile', compact('ukm'));
    }

    // 🔥 proses join
    public function join(Request $request)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
        ]);

        $userId = auth()->id();
        $ukm = UKM::findOrFail($request->ukm_id);

        if (!$ukm->is_recruitment_open) {
            return back()->with('error', 'Maaf, pendaftaran untuk UKM ini sedang ditutup');
        }

        // cek sudah pernah join
        $exists = Membership::where('user_id', $userId)
            ->where('ukm_id', $request->ukm_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mendaftar UKM ini');
        }

        // create membership (pending)
        Membership::create([
            'user_id' => $userId,
            'ukm_id' => $request->ukm_id,
            'role_in_ukm' => 'member',
            'status' => 'pending',
        ]);

        return redirect('/member/dashboard')
            ->with('success', 'Berhasil mendaftar, menunggu persetujuan');
    }
}