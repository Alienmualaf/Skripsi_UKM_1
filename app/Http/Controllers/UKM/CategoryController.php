<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\LetterCategory;
use App\Models\InventoryCategory;
use App\Models\Letter;
use App\Models\Inventory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Fetch letter categories and dynamically count their usages
        $letterCategories = LetterCategory::all()->map(function($cat) {
            $cat->letters_count = Letter::where('type', $cat->name)->count();
            return $cat;
        });

        // Fetch inventory categories and dynamically count their usages
        $inventoryCategories = InventoryCategory::all()->map(function($cat) {
            $cat->inventories_count = Inventory::where('category', $cat->name)->count();
            return $cat;
        });

        return view('pengurus.categories.index', compact(
            'letterCategories', 
            'inventoryCategories'
        ));
    }

    // --- LETTER CATEGORIES ---
    public function storeLetter(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:letter_categories,name',
        ]);

        LetterCategory::create($data);

        return back()->with('success', 'Kategori surat berhasil ditambahkan.');
    }

    public function updateLetter(Request $request, $id)
    {
        $category = LetterCategory::findOrFail($id);
        $oldName = $category->name;

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:letter_categories,name,' . $id,
        ]);

        $category->update($data);

        // Sync old references in letters table to the new name
        Letter::where('type', $oldName)->update(['type' => $data['name']]);

        return back()->with('success', 'Kategori surat berhasil diperbarui dan disinkronisasikan.');
    }

    public function destroyLetter($id)
    {
        $category = LetterCategory::findOrFail($id);
        if (Letter::where('type', $category->name)->exists()) {
            return back()->with('error', 'Kategori surat tidak bisa dihapus karena masih digunakan oleh beberapa arsip surat.');
        }
        $category->delete();
        return back()->with('success', 'Kategori surat berhasil dihapus.');
    }

    // --- INVENTORY CATEGORIES ---
    public function storeInventory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name',
        ]);

        InventoryCategory::create($data);

        return back()->with('success', 'Kategori inventaris berhasil ditambahkan.');
    }

    public function updateInventory(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);
        $oldName = $category->name;

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $id,
        ]);

        $category->update($data);

        // Sync old references in inventories table to the new name
        Inventory::where('category', $oldName)->update(['category' => $data['name']]);

        return back()->with('success', 'Kategori inventaris berhasil diperbarui dan disinkronisasikan.');
    }

    public function destroyInventory($id)
    {
        $category = InventoryCategory::findOrFail($id);
        if (Inventory::where('category', $category->name)->exists()) {
            return back()->with('error', 'Kategori inventaris tidak bisa dihapus karena masih digunakan oleh beberapa aset barang.');
        }
        $category->delete();
        return back()->with('success', 'Kategori inventaris berhasil dihapus.');
    }
}
