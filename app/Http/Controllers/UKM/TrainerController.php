<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Daftar pelatih (pengurus - bisa tambah & hapus)
     */
    public function index()
    {
        $trainers = Trainer::latest()->get();
        return view('pengurus.trainers.index', compact('trainers'));
    }

    /**
     * Form tambah pelatih (pengurus only)
     */
    public function create()
    {
        return view('pengurus.trainers.create');
    }

    /**
     * Simpan pelatih baru (pengurus only)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone'     => 'nullable|string|max:20',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        Trainer::create($data);

        return redirect()->route('pengurus.trainers.index')
            ->with('success', 'Pelatih berhasil ditambahkan.');
    }

    /**
     * Form edit pelatih (pengurus only)
     */
    public function edit($id)
    {
        $trainer = Trainer::findOrFail($id);
        return view('pengurus.trainers.edit', compact('trainer'));
    }

    /**
     * Update data pelatih (pengurus only)
     */
    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone'     => 'nullable|string|max:20',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($trainer->photo && \Storage::disk('public')->exists($trainer->photo)) {
                \Storage::disk('public')->delete($trainer->photo);
            }
            $data['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        $trainer->update($data);

        return redirect()->route('pengurus.trainers.index')
            ->with('success', 'Pelatih berhasil diperbarui.');
    }

    /**
     * Hapus pelatih (pengurus only)
     */
    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        if ($trainer->photo && \Storage::disk('public')->exists($trainer->photo)) {
            \Storage::disk('public')->delete($trainer->photo);
        }
        $trainer->delete();

        return back()->with('success', 'Pelatih berhasil dihapus.');
    }
}
