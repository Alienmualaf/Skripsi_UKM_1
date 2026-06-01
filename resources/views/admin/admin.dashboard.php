@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Pusat Kendali Administrator</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Berperan sebagai pengelola sistem secara keseluruhan yang berfokus pada pengawasan, monitoring, dan pengeditan data secara global.</p>
</div>

<!-- Aggregated Metrics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-users-three"></i> Total Pengguna
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.5rem;">{{ $totalUsers }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Akun terdaftar global</div>
    </div>
    
    <div class="card stat-card" style="border-top: 3px solid var(--success-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-house-line"></i> Terdaftar UKM
        </div>
        <div class="stat-value text-success" style="font-size: 1.5rem; font-weight: 800; margin-top: 0.5rem;">{{ $totalUKM }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Unit Kegiatan Mahasiswa</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-student"></i> Anggota Aktif
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--accent-color); margin-top: 0.5rem;">{{ $totalMembers }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Keanggotaan approved</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--warning-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-calendar-blank"></i> Agenda Terlaksana
        </div>
        <div class="stat-value text-warning" style="font-size: 1.5rem; font-weight: 800; margin-top: 0.5rem;">{{ $totalEvents }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Total kegiatan UKM</div>
    </div>

</div>

<!-- Graphs Panel -->
<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Doughnut Chart: Membership Distribution -->
    <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h3 class="mb-4" style="font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
            <i class="ph ph-chart-pie-slice" style="color: var(--accent-color);"></i> Distribusi Anggota
        </h3>
        <div style="height: 280px; position: relative; max-width: 480px; margin: 0 auto; width: 100%;">
            <canvas id="memberDistributionChart"></canvas>
        </div>
    </div>
</div>

<!-- UKM Health Metrics Overview -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 1.5rem !important;">
    <h3 class="mb-4" style="font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph ph-activity" style="color: var(--accent-color);"></i> Status Operasional UKM
    </h3>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama UKM</th>
                    <th>Anggota Aktif</th>
                    <th>Jumlah Kegiatan</th>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Kas Bersih</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ukmData as $data)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $data['name'] }}</td>
                    <td style="font-weight: 600; color: var(--text-secondary);">{{ $data['members_count'] }} Anggota</td>
                    <td style="font-weight: 600; color: var(--text-secondary);">{{ $data['events_count'] }} Agenda</td>
                    <td class="text-success" style="font-weight: 600;">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                    <td class="text-danger" style="font-weight: 600;">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                    <td style="font-weight: 700; color: var(--{{ $data['balance'] >= 0 ? 'success' : 'danger' }}-color);">
                        Rp {{ number_format($data['balance'], 0, ',', '.') }}
                    </td>
                    <td>
                        <a href="/admin/ukm/{{ $data['id'] }}/edit" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 0.375rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <i class="ph ph-pencil-simple"></i> Kelola
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- One Column: Recent Registrations -->
<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Box 1: Recent Users -->
    <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h3 class="mb-4" style="font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Pengguna Baru Terdaftar
        </h3>
        <div class="table-wrapper">
            <table class="table" style="font-size: 0.85rem;">
                <thead>
                    <tr>
                        <th>Nama / Email</th>
                        <th>Peran</th>
                        <th>Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $user->email }}</div>
                        </td>
                        <td>
                            <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700; font-size: 0.7rem;">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.75rem;">
                            {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="{{ asset('js/chart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const style = getComputedStyle(document.documentElement);
        const accentColor = style.getPropertyValue('--accent-color').trim() || '#3b82f6';
        const successColor = style.getPropertyValue('--success-color').trim() || '#10b981';
        const dangerColor = style.getPropertyValue('--danger-color').trim() || '#ef4444';
        
        // Data pluck
        const ukmNames = {!! json_encode(collect($ukmData)->pluck('name')) !!};
        const ukmMembers = {!! json_encode(collect($ukmData)->pluck('members_count')) !!};

        // 1. Doughnut Chart: Member Distribution
        const memberCtx = document.getElementById('memberDistributionChart').getContext('2d');
        new Chart(memberCtx, {
            type: 'doughnut',
            data: {
                labels: ukmNames,
                datasets: [{
                    data: ukmMembers,
                    backgroundColor: [
                        accentColor + 'cc',
                        successColor + 'cc',
                        '#f59e0bcc',
                        '#8b5cf6cc',
                        '#ec4899cc',
                        '#06b6d4cc'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection