<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Finance;
use App\Models\Inventory;
use App\Models\Gallery;
use App\Models\Material;
use App\Models\UKM;
use App\Models\Announcement;
use App\Models\Membership;
use App\Models\Coach;
use App\Models\Attendance;
use App\Models\CoachAttendance;
use App\Models\UKMClassification;
use Illuminate\Http\Request;

class GlobalDataController extends Controller
{
    public function events(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Event::with('ukm')->latest();
        
        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $events = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;
        
        return view('admin.events.index', compact('events', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyEvent(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:upcoming,ongoing,completed',
            'location' => 'nullable|string|max:255',
        ]);

        $event->update($request->only('title', 'status', 'location'));
        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function finances(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Finance::with('ukm')->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $finances = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.finances.index', compact('finances', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyFinance(Finance $finance)
    {
        $finance->delete();
        return back()->with('success', 'Data keuangan berhasil dihapus.');
    }

    public function updateFinance(Request $request, Finance $finance)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:income,expense',
            'amount' => 'required|numeric',
        ]);

        $finance->update($request->only('title', 'type', 'amount'));
        return back()->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function inventories(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Inventory::with('ukm')->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('condition', 'like', "%{$search}%");
            });
        }

        $inventories = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.inventories.index', compact('inventories', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyInventory(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Data inventaris berhasil dihapus.');
    }

    public function updateInventory(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|string|in:good,damaged,lost',
        ]);

        $inventory->update($request->only('name', 'quantity', 'condition'));
        return back()->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function galleries(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Gallery::with('ukm')->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $galleries = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.galleries.index', compact('galleries', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyGallery(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Data galeri berhasil dihapus.');
    }

    public function updateGallery(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $gallery->update($request->only('title'));
        return back()->with('success', 'Data galeri berhasil diperbarui.');
    }

    public function materials(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Material::with('ukm')->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $materials = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.materials.index', compact('materials', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();
        return back()->with('success', 'Data materi berhasil dihapus.');
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $material->update($request->only('title', 'description'));
        return back()->with('success', 'Data materi berhasil diperbarui.');
    }

    public function announcements(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Announcement::with(['ukm', 'event', 'creator'])->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.announcements.index', compact('announcements', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Data pengumuman berhasil dihapus.');
    }

    public function memberships(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Membership::with(['ukm', 'user', 'classification'])->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }
        if ($request->filled('role')) {
            $query->where('role_in_ukm', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $search = $request->input('search');
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $memberships = $query->paginate(15)->withQueryString();
        $classifications = UKMClassification::with('ukm')->get();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.memberships.index', compact('memberships', 'ukms', 'classifications', 'selectedUkm', 'search'));
    }

    public function destroyMembership(Membership $membership)
    {
        $membership->delete();
        return back()->with('success', 'Data keanggotaan berhasil dihapus.');
    }

    public function coaches(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Coach::with('ukm')->latest();

        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $coaches = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.coaches.index', compact('coaches', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyCoach(Coach $coach)
    {
        $coach->delete();
        return back()->with('success', 'Data pelatih/pembina berhasil dihapus.');
    }

    public function attendances(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = Attendance::with(['event', 'session', 'user', 'event.ukm'])->latest();

        if ($request->filled('ukm_id')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('ukm_id', $request->ukm_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhereHas('event', function($qe) use ($search) {
                    $qe->where('title', 'like', "%{$search}%");
                });
            });
        }

        $attendances = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.attendances.index', compact('attendances', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyAttendance(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data absensi anggota berhasil dihapus.');
    }

    public function coachAttendances(Request $request)
    {
        $ukms = UKM::orderBy('name')->get();
        $query = CoachAttendance::with(['event', 'session', 'coach', 'event.ukm'])->latest();

        if ($request->filled('ukm_id')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('ukm_id', $request->ukm_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('coach', function($qc) use ($search) {
                    $qc->where('name', 'like', "%{$search}%");
                })->orWhereHas('event', function($qe) use ($search) {
                    $qe->where('title', 'like', "%{$search}%");
                });
            });
        }

        $coachAttendances = $query->paginate(15)->withQueryString();
        $selectedUkm = $request->filled('ukm_id') ? $ukms->firstWhere('id', $request->ukm_id) : null;

        return view('admin.coach_attendances.index', compact('coachAttendances', 'ukms', 'selectedUkm', 'search'));
    }

    public function destroyCoachAttendance(CoachAttendance $coachAttendance)
    {
        $coachAttendance->delete();
        return back()->with('success', 'Data absensi pelatih berhasil dihapus.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function updateMembership(Request $request, Membership $membership)
    {
        $request->validate([
            'role_in_ukm' => 'required|string|in:admin,member',
            'status' => 'required|string|in:pending,approved',
            'ukm_classification_id' => 'nullable|exists:ukm_classifications,id',
        ]);

        $membership->update([
            'role_in_ukm' => $request->role_in_ukm,
            'status' => $request->status,
            'ukm_classification_id' => $request->ukm_classification_id,
        ]);

        return back()->with('success', 'Keanggotaan berhasil diperbarui.');
    }

    public function updateCoach(Request $request, Coach $coach)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:pelatih,bph,lainnya',
            'skills' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $coach->update([
            'name' => $request->name,
            'category' => $request->category,
            'skills' => $request->skills,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Data pelatih/pembina berhasil diperbarui.');
    }

    public function updateAttendance(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|string|in:hadir,sakit,izin,alpa',
        ]);

        $attendance->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Data absensi anggota berhasil diperbarui.');
    }

    public function updateCoachAttendance(Request $request, CoachAttendance $coachAttendance)
    {
        $request->validate([
            'status' => 'required|string|in:hadir,izin,tidak hadir',
            'notes' => 'nullable|string',
        ]);

        $coachAttendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Data absensi pelatih berhasil diperbarui.');
    }
}
