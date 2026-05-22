<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Membership;

class MemberController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');

        $members = Membership::with(['user', 'classification'])
            ->where('ukm_id', $ukmId)
            ->get();

        $classifications = \App\Models\UKMClassification::where('ukm_id', $ukmId)->get();

        return view('ukm.members.index', compact('members', 'classifications'));
    }

    public function approve($id)
    {

        $ukmId = session('managed_ukm_id');
        $member = Membership::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $member->update(['status' => 'approved']);

        return back()->with('success', 'Anggota disetujui');
    }

    public function destroy($id)
    {
        $ukmId = session('managed_ukm_id');
        $member = Membership::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        if($member->role_in_ukm === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus sesama admin');
        }
        
        $member->delete();

        return back()->with('success', 'Anggota dihapus/ditolak');
    }

    public function updateClassification(\Illuminate\Http\Request $request, $id)
    {
        $ukmId = session('managed_ukm_id');
        $member = Membership::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $request->validate(['ukm_classification_id' => 'nullable|exists:ukm_classifications,id']);
        $member->update(['ukm_classification_id' => $request->ukm_classification_id]);

        return back()->with('success', 'Klasifikasi anggota berhasil diperbarui');
    }

    public function updateRole(\Illuminate\Http\Request $request, $id)
    {
        $ukmId = session('managed_ukm_id');
        $member = Membership::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $request->validate(['role_in_ukm' => 'required|in:admin,member']);
        
        // Cek jika demote admin terakhir
        if ($member->role_in_ukm === 'admin' && $request->role_in_ukm !== 'admin') {
            $adminCount = Membership::where('ukm_id', $ukmId)
                ->where('role_in_ukm', 'admin')
                ->where('status', 'approved')
                ->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Gagal mengubah peran. Harus ada minimal 1 Admin UKM aktif.');
            }
        }

        $member->update(['role_in_ukm' => $request->role_in_ukm]);

        return back()->with('success', 'Peran anggota berhasil diperbarui menjadi ' . ucfirst($request->role_in_ukm));
    }
}