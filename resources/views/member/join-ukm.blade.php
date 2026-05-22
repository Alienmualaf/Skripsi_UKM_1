@extends('layouts.app')

@section('title', 'Daftar UKM Baru')
@section('header', 'Eksplorasi UKM')

@section('content')
<p class="mb-4">Temukan Unit Kegiatan Mahasiswa yang sesuai dengan minat dan bakat Anda.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    @foreach($ukms as $ukm)
        @php
            $alreadyJoined = auth()->user()->memberships()->where('ukm_id', $ukm->id)->first();
        @endphp
        
        <div class="card" style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background-color: var(--accent-light); color: var(--accent-color); border-radius: 0.5rem; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; font-weight: bold; overflow: hidden;">
                    @if($ukm->logo)
                        <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/logoup.png') }}" style="width: 70%; height: 70%; object-fit: contain; opacity: 0.5;">
                    @endif
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.125rem;">{{ $ukm->name }}</h3>
                    <span class="text-secondary" style="font-size: 0.875rem;">{{ $ukm->memberships()->where('status', 'approved')->count() }} Anggota Aktif</span>
                </div>
            </div>
            
            <p style="color: var(--text-secondary); font-size: 0.95rem; flex: 1;">
                {{ Str::limit($ukm->description, 100) }}
            </p>
            
            <div style="margin-top: auto;">
                @if($alreadyJoined)
                    @if($alreadyJoined->status == 'approved')
                    <div style="display: flex; gap: 0.5rem; width: 100%;">
                        <a href="/member/ukm/{{ $ukm->id }}" class="btn" style="flex: 1; background-color: var(--bg-color); color: var(--accent-color); border: 1px solid var(--accent-color); text-align: center;"><i class="ph ph-eye"></i> Profil</a>
                        <button class="btn" style="flex: 1; background-color: var(--bg-color); color: var(--success-color); border: 1px solid var(--border-color);" disabled>
                            <i class="ph-fill ph-check-circle"></i> Bergabung
                        </button>
                    </div>
                    @else
                    <div style="display: flex; gap: 0.5rem; width: 100%;">
                        <a href="/member/ukm/{{ $ukm->id }}" class="btn" style="flex: 1; background-color: var(--bg-color); color: var(--accent-color); border: 1px solid var(--accent-color); text-align: center;"><i class="ph ph-eye"></i> Profil</a>
                        <button class="btn" style="flex: 1; background-color: var(--bg-color); color: var(--text-secondary); border: 1px solid var(--border-color);" disabled>
                            <i class="ph ph-hourglass"></i> Menunggu
                        </button>
                    </div>
                    @endif
                @else
                    <div style="display: flex; gap: 0.5rem; width: 100%;">
                        <a href="/member/ukm/{{ $ukm->id }}" class="btn" style="flex: 1; background-color: var(--bg-color); color: var(--accent-color); border: 1px solid var(--accent-color); text-align: center;"><i class="ph ph-eye"></i> Profil</a>
                        <form action="/member/join" method="POST" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="ukm_id" value="{{ $ukm->id }}">
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="ph ph-user-plus"></i> Daftar</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    
    @if(count($ukms) == 0)
    <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
        <i class="ph ph-buildings text-secondary" style="font-size: 3rem; margin-bottom: 1rem;"></i>
        <p class="text-secondary">Belum ada UKM yang terdaftar di sistem.</p>
    </div>
    @endif
</div>
@endsection