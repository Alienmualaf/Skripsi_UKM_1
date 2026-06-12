<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        $query = Letter::with('program');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('letter_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $letters = $query->orderBy('date', 'desc')->paginate(10);
        return view('pengurus.letters.index', compact('letters'));
    }

    public function create()
    {
        $programs = \App\Models\Program::active()->get();
        return view('pengurus.letters.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'letter_number' => 'required|string|max:100',
            'date' => 'required|date',
            'subject' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'type' => 'required|in:Surat Tugas,Surat Permohonan,Surat Undangan,Surat Peminjaman,Surat Keterangan',
            'file_path' => 'required|file|mimes:pdf|max:5120', // Max 5MB
            'related_to' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:related_to,Program Kerja|nullable|exists:programs,id',
        ]);

        if ($data['related_to'] === 'Umum') {
            $data['program_id'] = null;
        }

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('letters', 'public');
            $data['file_path'] = $path;
        }

        Letter::create($data);

        return redirect()->route('pengurus.letters.index')->with('success', 'Surat berhasil diarsipkan.');
    }

    public function edit($id)
    {
        $letter = Letter::findOrFail($id);
        $programs = \App\Models\Program::active()->get();
        return view('pengurus.letters.edit', compact('letter', 'programs'));
    }

    public function update(Request $request, $id)
    {
        $letter = Letter::findOrFail($id);

        $data = $request->validate([
            'letter_number' => 'required|string|max:100',
            'date' => 'required|date',
            'subject' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'type' => 'required|in:Surat Tugas,Surat Permohonan,Surat Undangan,Surat Peminjaman,Surat Keterangan',
            'file_path' => 'nullable|file|mimes:pdf|max:5120',
            'related_to' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:related_to,Program Kerja|nullable|exists:programs,id',
        ]);

        if ($data['related_to'] === 'Umum') {
            $data['program_id'] = null;
        }

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('letters', 'public');
            $data['file_path'] = $path;
        }

        $letter->update($data);

        return redirect()->route('pengurus.letters.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $letter = Letter::findOrFail($id);
        $letter->delete();
        return back()->with('success', 'Surat berhasil dihapus.');
    }
}
