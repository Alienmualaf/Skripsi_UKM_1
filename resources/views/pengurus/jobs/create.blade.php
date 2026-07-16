@extends('layouts.app')

@section('title', 'Tambah Penugasan')
@section('header', 'Tambah Penugasan PSUP')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Tambah Penugasan Tampil</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Buat detail konser/acara tampil baru dan tugaskan penyanyi.</p>
</div>

<div class="card" style="padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Detail Penugasan
    </h4>

    <form action="{{ route('pengurus.jobs.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Penugasan / Konser</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}" placeholder="Contoh: Gigs Wisuda UP Semester Genap" style="padding: 0.65rem;">
                    @error('title') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi Penugasan</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Menyanyikan lagu Hymne Universitas Pancasila dan Bagimu Negeri." style="padding: 0.65rem;">{{ old('description') }}</textarea>
                    @error('description') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal <span style="color: var(--danger-color);">*</span></label>
                        <input type="date" name="date" class="form-control" required value="{{ old('date') }}" style="padding: 0.65rem;">
                        @error('date') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jam / Waktu <span style="color: var(--danger-color);">*</span></label>
                        <input type="time" name="performance_time" class="form-control" required value="{{ old('performance_time', '17:00') }}" style="padding: 0.65rem;">
                        @error('performance_time') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Lokasi Venue</label>
                    <input type="text" name="location" class="form-control" required value="{{ old('location') }}" placeholder="Contoh: Gedung Serbaguna UP lt. 2" style="padding: 0.65rem;">
                    @error('location') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <div class="form-group mb-4">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 700; font-size: 0.85rem; color: var(--text-primary);">
                        <input type="checkbox" name="show_on_landing" value="1" {{ old('show_on_landing') ? 'checked' : '' }} style="width: auto; margin: 0; cursor: pointer;">
                        Tampilkan di Landing Page
                    </label>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-top: 0.25rem;">Jika dicentang, penugasan/job ini akan ditampilkan pada section "Penampilan & Kegiatan" di landing page publik.</span>
                </div>
            </div>

            <!-- Member Checkbox Lists (Grouped by Voice Range) -->
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.75rem; display: block;">Tugaskan Anggota (Penyanyi)</label>
                
                <div style="border: 1px solid var(--border-color); border-radius: 8px; max-height: 350px; overflow-y: auto; padding: 1rem; background: var(--bg-color);">
                    @php
                        $groupedMembers = $members->groupBy(function($m) {
                            return $m->voiceClassification->name ?? 'Belum Diklasifikasi';
                        });
                    @endphp

                    @forelse($groupedMembers as $voiceName => $memberList)
                        <div style="margin-bottom: 1rem;">
                            <h5 style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--accent-color); border-bottom: 1px solid var(--border-color); padding-bottom: 0.25rem; margin: 0 0 0.5rem 0;">
                                {{ $voiceName }}
                            </h5>
                            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                                @foreach($memberList as $m)
                                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; cursor: pointer; color: var(--text-primary);">
                                        <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" style="width: 1rem; height: 1rem;">
                                        <span>{{ $m->user->name ?? 'User' }} ({{ $m->nim ?? '-' }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin: 2rem 0;">Belum ada anggota aktif terdaftar.</p>
                    @endforelse
                </div>
                @error('member_ids') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Penugasan</button>
            <a href="{{ route('pengurus.jobs.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
