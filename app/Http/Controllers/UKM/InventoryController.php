<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        // Ambil maksimal 10 barang inventaris terbaru
        $inventories = Inventory::with('event')->where('ukm_id', $ukmId)->latest()->take(10)->get();
        // Hitung total barang inventaris
        $totalInventories = Inventory::where('ukm_id', $ukmId)->count();
        $events = \App\Models\Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.inventories.index', compact('inventories', 'events', 'totalInventories'));
    }

    public function all(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $search = $request->input('search');
        $condition = $request->input('condition');

        $query = Inventory::with('event')->where('ukm_id', $ukmId)->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($condition) {
            $query->where('condition', $condition);
        }

        $inventories = $query->paginate(15)->withQueryString();
        
        return view('ukm.inventories.all', compact('inventories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:good,damaged,lost',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'event_id' => 'nullable|exists:events,id',
        ]);

        Inventory::create([
            'ukm_id' => session('managed_ukm_id'),
            'created_by' => auth()->id(),
            'name' => $request->name,
            'quantity' => $request->quantity,
            'condition' => $request->condition,
            'location' => $request->location,
            'description' => $request->description,
            'event_id' => $request->event_id,
        ]);

        return back()->with('success', 'Barang inventaris berhasil ditambahkan');
    }

    public function destroy($id)
    {

        $ukmId = session('managed_ukm_id');
        $inventory = Inventory::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        $inventory->delete();

        return back()->with('success', 'Barang inventaris berhasil dihapus');
    }
}