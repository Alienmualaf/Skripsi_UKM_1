@extends('layouts.app')

@section('title', 'Pengaturan Website')
@section('header', 'Pengaturan Website & SMTP')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Konfigurasi Platform & Layanan</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Atur identitas platform, integrasi pengiriman email SMTP, notifikasi otomatis, dan driver penyimpanan file.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr; lg:grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        
        <!-- Left Side: Site Info & SMTP Config -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Identitas Website -->
            <div class="card" style="padding: 1.5rem;">
                <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                    <i class="ph ph-globe" style="color: var(--accent-color);"></i> Identitas Platform PSUP
                </h4>
                
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Nama Website / Aplikasi</label>
                    <input type="text" name="website_name" class="form-control" value="{{ old('website_name', $settings['website_name'] ?? '') }}" required style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Teks Footer Hak Cipta</label>
                    <input type="text" name="footer_text" class="form-control" value="{{ old('footer_text', $settings['footer_text'] ?? '') }}" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Email Kontak Resmi</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">No. Telepon / WhatsApp</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" required style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Alamat Sekretariat</label>
                    <textarea name="address" class="form-control" style="font-size: 0.875rem; border-radius: 8px; min-height: 80px; padding: 0.5rem;">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Facebook URL</label>
                        <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Instagram URL</label>
                        <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">YouTube URL</label>
                        <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                </div>
            </div>

            <!-- Email & SMTP Setup -->
            <div class="card" style="padding: 1.5rem;">
                <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                    <i class="ph ph-envelope-simple" style="color: var(--accent-color);"></i> Konfigurasi SMTP Mail Server
                </h4>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Host SMTP</label>
                        <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}" placeholder="smtp.mailtrap.io" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Port SMTP</label>
                        <input type="text" name="smtp_port" class="form-control" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}" placeholder="587" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Username SMTP</label>
                        <input type="text" name="smtp_user" class="form-control" value="{{ old('smtp_user', $settings['smtp_user'] ?? '') }}" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Password SMTP</label>
                        <input type="password" name="smtp_pass" class="form-control" value="{{ old('smtp_pass', $settings['smtp_pass'] ?? '') }}" placeholder="••••••••••••" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group mb-4" style="width: 200px;">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Enkripsi Email (Encryption)</label>
                    <select name="smtp_enc" class="form-control" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                        <option value="tls" {{ (old('smtp_enc', $settings['smtp_enc'] ?? '') == 'tls') ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ (old('smtp_enc', $settings['smtp_enc'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ (old('smtp_enc', $settings['smtp_enc'] ?? '') == 'none') ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Right Side: Notification Configurations & Storage Driver -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Notifikasi & Pengiriman -->
            <div class="card" style="padding: 1.5rem;">
                <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                    <i class="ph ph-bell" style="color: var(--accent-color);"></i> Kontrol Notifikasi
                </h4>
                
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.25rem;">
                    <!-- Email Notif -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                        <div>
                            <div style="font-weight: 700; font-size: 0.8125rem; color: var(--text-primary);">Notifikasi Email</div>
                            <div style="font-size: 0.7rem; color: var(--text-secondary);">Kirim alert registrasi & agenda ke email</div>
                        </div>
                        <input type="checkbox" name="notify_email" value="1" {{ (old('notify_email', $settings['notify_email'] ?? '') == '1') ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                    </div>

                    <!-- System Notif -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                        <div>
                            <div style="font-weight: 700; font-size: 0.8125rem; color: var(--text-primary);">Notifikasi Sistem (Web)</div>
                            <div style="font-size: 0.7rem; color: var(--text-secondary);">Simpan pemberitahuan di dashboard</div>
                        </div>
                        <input type="checkbox" name="notify_system" value="1" {{ (old('notify_system', $settings['notify_system'] ?? '') == '1') ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                    </div>

                    <!-- Announcement Notif -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem;">
                        <div>
                            <div style="font-weight: 700; font-size: 0.8125rem; color: var(--text-primary);">Broadcast Pengumuman</div>
                            <div style="font-size: 0.7rem; color: var(--text-secondary);">Aktifkan rilis banner pengumuman ukm</div>
                        </div>
                        <input type="checkbox" name="notify_announce" value="1" {{ (old('notify_announce', $settings['notify_announce'] ?? '') == '1') ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                    </div>
                </div>
            </div>

            <!-- Storage Driver -->
            <div class="card" style="padding: 1.5rem;">
                <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                    <i class="ph ph-hard-drives" style="color: var(--accent-color);"></i> Driver Penyimpanan
                </h4>
                
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Storage Driver Aktif</label>
                    <select name="storage_driver" class="form-control" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
                        <option value="public" {{ (old('storage_driver', $settings['storage_driver'] ?? '') == 'public') ? 'selected' : '' }}>Local (Public Storage)</option>
                        <option value="s3" {{ (old('storage_driver', $settings['storage_driver'] ?? '') == 's3') ? 'selected' : '' }}>AWS S3 Cloud Storage</option>
                        <option value="google" {{ (old('storage_driver', $settings['storage_driver'] ?? '') == 'google') ? 'selected' : '' }}>Google Drive API</option>
                    </select>
                </div>
            </div>

            <!-- Save settings trigger -->
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 2.75rem; font-weight: 700; font-size: 0.9rem; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem;">
                <i class="ph ph-floppy-disk" style="font-size: 1.25rem;"></i> Simpan Semua Konfigurasi
            </button>

        </div>

    </div>
</form>

<!-- Test SMTP mail server panel -->
<div class="card" style="padding: 1.5rem; margin-top: 1.5rem;">
    <h4 style="margin: 0 0 0.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-paper-plane-tilt" style="color: var(--accent-color);"></i> Test Pengiriman Email SMTP
    </h4>
    <p style="margin: 0 0 1.25rem 0; color: var(--text-secondary); font-size: 0.8125rem; line-height: 1.5;">Kirim email test ke alamat email eksternal Anda untuk memastikan setingan SMTP host dan port di atas valid dan tidak terblokir firewall.</p>
    
    <form action="{{ route('admin.settings.email-test') }}" method="POST" style="display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap;">
        @csrf
        <div style="flex: 1; min-width: 260px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kirim Email Test Ke:</label>
            <input type="email" name="test_email" required class="form-control" placeholder="contoh@gmail.com" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;">
        </div>
        <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1.25rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-paper-plane"></i> Kirim Test Email
        </button>
    </form>
</div>
@endsection
