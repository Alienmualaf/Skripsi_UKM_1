@extends('layouts.app')

@section('title', 'Riwayat Pengiriman Email')
@section('header', 'Riwayat Pengiriman Email')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Log & Status Email Rekrutmen</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pantau status pengiriman email otomatis kepada calon anggota baru PSUP.</p>
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-paper-plane-tilt" style="color: var(--accent-color);"></i> Daftar Pengiriman Email
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu Kirim</th>
                    <th>Nama Penerima</th>
                    <th>Email Penerima</th>
                    <th>Subjek Email</th>
                    <th>Status</th>
                    <th>Pesan Error</th>
                    <th style="width: 220px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 600;">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $log->recipient_name }}</td>
                    <td style="color: var(--text-secondary); font-size: 0.85rem;">{{ $log->recipient_email }}</td>
                    <td style="color: var(--text-primary); font-size: 0.85rem; font-weight: 600; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->subject }}">
                        {{ $log->subject }}
                    </td>
                    <td>
                        @if($log->status === 'success')
                            <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-weight: bold; border: 1px solid rgba(16,185,129,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Berhasil</span>
                        @else
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); font-weight: bold; border: 1px solid rgba(239,68,68,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Gagal</span>
                        @endif
                    </td>
                    <td style="color: var(--danger-color); font-size: 0.75rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->error_message }}">
                        {{ $log->error_message ?? '-' }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <!-- View Detail Content Button -->
                            <button onclick="showEmailPreview({{ $log->id }}, {!! json_encode($log->subject) !!})" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 600; border: 1px solid var(--border-color); background: var(--surface-color); color: var(--text-primary);">
                                <i class="ph ph-eye"></i> Detail
                            </button>
                            
                            <!-- Resend Action Form -->
                            <form action="{{ route('ukm.email-logs.resend', $log->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 600;">
                                    <i class="ph ph-arrows-clockwise"></i> Kirim Ulang
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Belum ada riwayat pengiriman email rekrutmen.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- Modal Dialog for Email Content Preview -->
<div id="emailPreviewModal" style="position: fixed; inset: 0; z-index: 99999; display: none; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; padding: 1.5rem;">
    <div style="background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 12px; width: 100%; max-width: 700px; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
        <!-- Modal Header -->
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #00072D; color: #fff;">
            <div>
                <h4 style="margin: 0; font-weight: bold; font-size: 1.05rem;" id="modalSubject">Detail Isi Email</h4>
                <div style="font-size: 0.75rem; opacity: 0.85; margin-top: 0.15rem;" id="modalMeta"></div>
            </div>
            <button onclick="closeEmailPreview()" style="background: none; border: none; color: #fff; cursor: pointer; padding: 0.25rem; font-size: 1.25rem;">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <!-- Modal Body (Iframe to isolate CSS) -->
        <div style="flex: 1; padding: 1.5rem; overflow-y: auto; background: #f8fafc;">
            <iframe id="emailContentFrame" style="width: 100%; height: 50vh; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff;" src="about:blank"></iframe>
        </div>
        <!-- Modal Footer -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; background: var(--bg-color);">
            <button onclick="closeEmailPreview()" class="btn btn-secondary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem; font-weight: 700;">Tutup</button>
        </div>
    </div>
</div>

<script>
    // Hidden email contents mapping
    const emailContents = {
        @foreach($logs as $log)
            "{{ $log->id }}": {!! json_encode($log->content) !!},
        @endforeach
    };

    function showEmailPreview(id, subject) {
        const modal = document.getElementById('emailPreviewModal');
        const frame = document.getElementById('emailContentFrame');
        const subEl = document.getElementById('modalSubject');
        
        if (emailContents[id]) {
            subEl.textContent = subject;
            modal.style.display = 'flex';
            
            // Wait for iframe load and populate
            const doc = frame.contentWindow.document;
            doc.open();
            doc.write(emailContents[id]);
            doc.close();
        }
    }

    function closeEmailPreview() {
        document.getElementById('emailPreviewModal').style.display = 'none';
        document.getElementById('emailContentFrame').src = 'about:blank';
    }
</script>
@endsection
