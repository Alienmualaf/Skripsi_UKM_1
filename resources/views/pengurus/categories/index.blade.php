@extends('layouts.app')

@section('title', 'Kelola Kategori')
@section('header', 'Kelola Kategori')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-x-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Pusat Manajemen Kategori</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola jenis persuratan, dan inventaris barang untuk menjaga kerapian data organisasi.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; align-items: start;">

    <!-- 2. KATEGORI SURAT -->
    <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-envelope-simple" style="color: var(--accent-color); font-size: 1.35rem;"></i> Kategori Surat
        </h4>

        <!-- Form Tambah -->
        @if(!auth()->user()->isAdminUkm())
        <form action="{{ route('pengurus.categories.letter.store') }}" method="POST" style="background: #f8fafc; border: 1px solid var(--border-color); padding: 1rem; border-radius: 8px; display: flex; flex-direction: column; gap: 0.75rem;">
            @csrf
            <div>
                <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Nama Jenis Surat</label>
                <input type="text" name="name" placeholder="Misal: Surat Permohonan Dana" required class="form-control" style="width: 100%; padding: 0.45rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.8125rem; font-weight: 700; width: 100%; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;">
                <i class="ph ph-plus"></i> Tambah Jenis Surat
            </button>
        </form>
        @endif

        <!-- List Items -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @forelse($letterCategories as $lc)
                <div style="border: 1px solid var(--border-color); padding: 0.75rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; background: white;">
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.9rem;">{{ $lc->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); font-weight: 600; margin-top: 0.2rem;">{{ $lc->letters_count }} arsip surat</div>
                    </div>
                    <div style="display: flex; gap: 0.25rem;">
                        <button onclick="openEditModal('letter', {{ $lc->id }}, '{{ $lc->name }}')" class="btn" style="padding: 0.35rem; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 6px;" title="Edit"><i class="ph ph-pencil-simple"></i></button>
                        <form action="{{ route('pengurus.categories.letter.destroy', $lc->id) }}" method="POST" onsubmit="return confirm('Hapus jenis surat ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.35rem; border-radius: 6px;" title="Hapus"><i class="ph ph-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; font-size: 0.8125rem; color: var(--text-secondary); padding: 1.5rem 0;">Belum ada kategori surat.</div>
            @endforelse
        </div>
    </div>

    <!-- 3. KATEGORI INVENTARIS -->
    <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-package" style="color: var(--accent-color); font-size: 1.35rem;"></i> Kategori Inventaris
        </h4>

        <!-- Form Tambah -->
        @if(!auth()->user()->isAdminUkm())
        <form action="{{ route('pengurus.categories.inventory.store') }}" method="POST" style="background: #f8fafc; border: 1px solid var(--border-color); padding: 1rem; border-radius: 8px; display: flex; flex-direction: column; gap: 0.75rem;">
            @csrf
            <div>
                <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Nama Kategori Aset</label>
                <input type="text" name="name" placeholder="Misal: Elektronik, Panggung" required class="form-control" style="width: 100%; padding: 0.45rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.8125rem; font-weight: 700; width: 100%; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;">
                <i class="ph ph-plus"></i> Tambah Kategori Aset
            </button>
        </form>
        @endif

        <!-- List Items -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @forelse($inventoryCategories as $ic)
                <div style="border: 1px solid var(--border-color); padding: 0.75rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; background: white;">
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.9rem;">{{ $ic->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); font-weight: 600; margin-top: 0.2rem;">{{ $ic->inventories_count }} aset terdaftar</div>
                    </div>
                    <div style="display: flex; gap: 0.25rem;">
                        <button onclick="openEditModal('inventory', {{ $ic->id }}, '{{ $ic->name }}')" class="btn" style="padding: 0.35rem; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 6px;" title="Edit"><i class="ph ph-pencil-simple"></i></button>
                        <form action="{{ route('pengurus.categories.inventory.destroy', $ic->id) }}" method="POST" onsubmit="return confirm('Hapus kategori inventaris ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.35rem; border-radius: 6px;" title="Hapus"><i class="ph ph-trash"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; font-size: 0.8125rem; color: var(--text-secondary); padding: 1.5rem 0;">Belum ada kategori inventaris.</div>
            @endforelse
        </div>
    </div>

</div>

<!-- EDIT MODAL (DIALOG NATIVE) -->
<dialog id="editCategoryDialog" style="border: none; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 1.5rem; width: 400px; max-width: 90%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 id="dialogTitle" style="margin: 0; font-weight: 800; color: var(--text-primary); font-size: 1.1rem;">Edit Kategori</h4>
        <button onclick="closeEditModal()" class="btn" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1; color: var(--text-secondary);">&times;</button>
    </div>
    
    <form id="editCategoryForm" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1rem;">
            <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Nama Kategori</label>
            <input type="text" id="editCategoryName" name="name" required class="form-control" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.875rem; margin-top: 0.25rem;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <button type="button" onclick="closeEditModal()" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">Batal</button>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem;">Simpan Perubahan</button>
        </div>
    </form>
</dialog>

<script>
    const dialog = document.getElementById('editCategoryDialog');
    const form = document.getElementById('editCategoryForm');
    const title = document.getElementById('dialogTitle');
    const nameInput = document.getElementById('editCategoryName');

    function openEditModal(group, id, name) {
        nameInput.value = name;
        
        if (group === 'letter') {
            title.textContent = 'Edit Kategori Surat';
            form.action = `/pengurus/categories/letter/${id}`;
        } else if (group === 'inventory') {
            title.textContent = 'Edit Kategori Inventaris';
            form.action = `/pengurus/categories/inventory/${id}`;
        }
        
        dialog.showModal();
    }

    function closeEditModal() {
        dialog.close();
    }

    // Close when clicking backdrop
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
            dialog.close();
        }
    });
</script>
@endsection
