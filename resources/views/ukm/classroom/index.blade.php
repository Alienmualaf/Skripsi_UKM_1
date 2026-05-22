@extends('layouts.app')

@section('title', 'Classroom Admin')
@section('header', 'Classroom Admin: ' . $ukm->name)

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .swal2-custom-popup {
        border-radius: 24px !important;
        font-family: 'Outfit', sans-serif !important;
        padding: 2rem !important;
    }
</style>

<div class="card animate-fade-in" style="border-radius: 24px; padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="font-weight: 800; font-size: 1.75rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">Pusat Kegiatan & Aktivitas Mengajar</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Pilih agenda di bawah ini untuk mengelola materi, pengumuman, dan mencatat absensi (GCR-style).</p>
        </div>
        <a href="/ukm/events" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
            <i class="ph ph-plus-circle"></i> Tambah Agenda Baru
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.75rem;">
        @forelse($events as $event)
        <div class="card" style="border: 1px solid var(--border-color); padding: 1.75rem; transition: all 0.3s; border-radius: 20px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; background: var(--surface-color);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--accent-color)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none'; this.style.borderColor='var(--border-color)';">
            
            @php
                $startDate = \Carbon\Carbon::parse($event->start_date);
                $endDate = \Carbon\Carbon::parse($event->end_date);
                $now = now();
                
                if ($now->lt($startDate)) {
                    $status = 'upcoming';
                    $badgeClass = 'badge-pending';
                } elseif ($now->gt($endDate)) {
                    $status = 'completed';
                    $badgeClass = 'badge-approved';
                } else {
                    $status = 'ongoing';
                    $badgeClass = 'badge-warning';
                }
            @endphp

            <div style="position: absolute; top: 0; right: 0; padding: 0.5rem 1rem; border-bottom-left-radius: 12px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.25rem;" class="badge {{ $badgeClass }}">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor; display: inline-block;"></span>
                {{ $status }}
            </div>
            
            <div style="margin-bottom: 1.5rem; margin-top: 1rem;">
                <h4 style="font-weight: 800; font-size: 1.35rem; line-height: 1.3; margin-bottom: 0.75rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $event->title }}</h4>
                
                <div style="display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-calendar" style="color: var(--accent-color); font-size: 1.1rem;"></i>
                        <span>{{ $event->start_date->format('d M Y') }}</span>
                    </div>
                    @if($event->location)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-map-pin" style="color: var(--danger-color); font-size: 1.1rem;"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: auto; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <div style="text-align: center; border-right: 1px solid var(--border-color); padding-right: 0.5rem;">
                    <div style="font-weight: 800; font-size: 1.2rem; color: var(--text-primary);">{{ $event->participants()->count() }}</div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; color: var(--text-secondary); font-weight: 700; letter-spacing: 0.05em;">Peserta</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-weight: 800; font-size: 1.2rem; color: var(--text-primary);">{{ $event->sessions()->count() }}</div>
                    <div style="font-size: 0.7rem; text-transform: uppercase; color: var(--text-secondary); font-weight: 700; letter-spacing: 0.05em;">Pertemuan</div>
                </div>
            </div>

            <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                <a href="/ukm/classroom/{{ $event->id }}" class="btn btn-primary" style="flex: 1; justify-content: center; font-size: 0.9rem; font-weight: 700; border-radius: 12px; padding: 0.8rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ph-fill ph-door-open" style="font-size: 1.1rem;"></i> Masuk Ruang
                </a>
                
                <form id="archive-form-{{ $event->id }}" action="/ukm/events/{{ $event->id }}/archive" method="POST" style="margin: 0;">
                    @csrf
                    <button type="button" onclick="confirmArchive({{ $event->id }}, '{{ addslashes($event->title) }}')" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 0.8rem; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Arsipkan Kegiatan" onmouseover="this.style.color='var(--accent-color)'; this.style.borderColor='var(--accent-color)';" onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-color)';">
                        <i class="ph ph-archive" style="font-size: 1.1rem;"></i>
                    </button>
                </form>

                <form id="delete-form-{{ $event->id }}" action="/ukm/events/{{ $event->id }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->title) }}')" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 0.8rem; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Hapus Kegiatan" onmouseover="this.style.color='var(--danger-color)'; this.style.borderColor='var(--danger-color)';" onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-color)';">
                        <i class="ph ph-trash" style="font-size: 1.1rem;"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; border: 2px dashed var(--border-color); border-radius: 20px;">
            <i class="ph ph-calendar-slash" style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
            <h4 style="font-weight: 700; color: var(--text-primary); font-size: 1.2rem;">Belum Ada Agenda Terdaftar</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Silakan buat agenda atau proker baru terlebih dahulu untuk mengakses Classroom.</p>
            <a href="/ukm/events" class="btn btn-primary" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 700;">
                Buat Agenda Baru &rarr;
            </a>
        </div>
        @endforelse
    </div>

    @if(isset($archivedEvents) && !$archivedEvents->isEmpty())
    <div style="margin-top: 4rem; border-top: 1px solid var(--border-color); padding-top: 2.5rem;">
        <div style="margin-bottom: 1.5rem;">
            <h4 style="font-weight: 800; font-size: 1.4rem; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem; margin: 0 0 0.25rem 0;">
                <i class="ph ph-archive" style="color: var(--text-secondary);"></i> Kegiatan Diarsipkan
            </h4>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">Daftar kegiatan yang telah diarsipkan. Kegiatan diarsipkan tidak muncul di feed mahasiswa.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.75rem; opacity: 0.85;">
            @foreach($archivedEvents as $event)
            <div class="card" style="border: 1px solid var(--border-color); padding: 1.75rem; border-radius: 20px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; background: var(--bg-color);">
                <div style="position: absolute; top: 0; right: 0; padding: 0.5rem 1rem; border-bottom-left-radius: 12px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; background: var(--border-color); color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ph ph-archive"></i> Diarsipkan
                </div>
                
                <div style="margin-bottom: 1.5rem; margin-top: 1rem;">
                    <h4 style="font-weight: 800; font-size: 1.35rem; line-height: 1.3; margin-bottom: 0.75rem; color: var(--text-secondary); font-family: 'Outfit', sans-serif;">{{ $event->title }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-calendar" style="color: var(--text-secondary); font-size: 1.1rem;"></i>
                            <span>{{ $event->start_date->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem; margin-top: auto; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                    <form id="unarchive-form-{{ $event->id }}" action="/ukm/events/{{ $event->id }}/unarchive" method="POST" style="margin: 0; flex: 1;">
                        @csrf
                        <button type="button" onclick="confirmUnarchive({{ $event->id }}, '{{ addslashes($event->title) }}')" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 0.85rem; font-weight: 700; border-radius: 12px; padding: 0.8rem; display: flex; align-items: center; gap: 0.35rem;">
                            <i class="ph ph-archive-out" style="font-size: 1.1rem;"></i> Pulihkan Kegiatan
                        </button>
                    </form>

                    <form id="delete-form-{{ $event->id }}" action="/ukm/events/{{ $event->id }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->title) }}')" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-secondary); padding: 0.8rem; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Hapus Kegiatan" onmouseover="this.style.color='var(--danger-color)'; this.style.borderColor='var(--danger-color)';" onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border-color)';">
                            <i class="ph ph-trash" style="font-size: 1.1rem;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function confirmArchive(eventId, eventTitle) {
        Swal.fire({
            title: 'Arsipkan Kegiatan?',
            html: `Apakah Anda yakin ingin mengarsipkan kegiatan <strong>"${eventTitle}"</strong>?<br><small style="color: var(--text-secondary);">Kegiatan yang diarsipkan tidak akan muncul pada feed sosial member.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Arsipkan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-custom-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('archive-form-' + eventId).submit();
            }
        });
    }

    function confirmDelete(eventId, eventTitle) {
        Swal.fire({
            title: 'Hapus Kegiatan?',
            html: `Apakah Anda yakin ingin menghapus kegiatan <strong>"${eventTitle}"</strong> secara permanen?<br><strong style="color: var(--danger-color); font-size: 0.85rem;">Perhatian: Semua data absensi, pertemuan, dan materi terkait akan dihapus permanen!</strong>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-custom-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + eventId).submit();
            }
        });
    }

    function confirmUnarchive(eventId, eventTitle) {
        Swal.fire({
            title: 'Pulihkan Kegiatan?',
            html: `Apakah Anda yakin ingin memulihkan kegiatan <strong>"${eventTitle}"</strong>?<br><small style="color: var(--text-secondary);">Kegiatan akan kembali aktif dan muncul di feed sosial member.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Aktifkan Kembali',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-custom-popup'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('unarchive-form-' + eventId).submit();
            }
        });
    }
</script>
@endsection
