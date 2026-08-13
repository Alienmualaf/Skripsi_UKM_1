<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryLoan;
use App\Models\InventoryCategory;
use App\Models\Member;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::orderBy('name', 'asc')->paginate(10);
        return view('pengurus.inventories.index', compact('inventories'));
    }

    public function create()
    {
        $programs = \App\Models\Program::active()->get();
        $categories = InventoryCategory::pluck('name')->toArray();
        return view('pengurus.inventories.create', compact('programs', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|exists:inventory_categories,name',
            'condition' => 'required|in:Baik,Rusak,Hilang',
            'quantity' => 'required|integer|min:1',
            'storage_location' => 'required|string|max:255',
            'used_for' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:used_for,Program Kerja|nullable|exists:programs,id',
        ]);

        if ($data['used_for'] === 'Umum') {
            $data['program_id'] = null;
        }

        Inventory::create($data);

        return redirect()->route('pengurus.inventories.index')->with('success', 'Barang inventaris berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $programs = \App\Models\Program::active()->get();
        $categories = InventoryCategory::pluck('name')->toArray();
        return view('pengurus.inventories.edit', compact('inventory', 'programs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|exists:inventory_categories,name',
            'condition' => 'required|in:Baik,Rusak,Hilang',
            'quantity' => 'required|integer|min:1',
            'storage_location' => 'required|string|max:255',
            'used_for' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:used_for,Program Kerja|nullable|exists:programs,id',
        ]);

        if ($data['used_for'] === 'Umum') {
            $data['program_id'] = null;
        }

        $inventory->update($data);

        return redirect()->route('pengurus.inventories.index')->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function allLoans()
    {
        $loans = InventoryLoan::with(['inventory', 'member.user', 'program'])->latest()->get();
        return view('pengurus.inventories.loans', compact('loans'));
    }

    public function loans($id)
    {
        $inventory = Inventory::findOrFail($id);
        $loans = InventoryLoan::where('inventory_id', $id)->with(['member', 'program'])->latest()->get();
        $members = Member::where('status', 'Anggota Aktif')->get();
        $programs = \App\Models\Program::active()->get();

        return view('pengurus.inventories.loans', compact('inventory', 'loans', 'members', 'programs'));
    }

    public function storeLoan(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $data = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'loan_letter' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
            'quantity' => 'required|integer|min:1|max:'.$inventory->available_qty,
            'condition_on_loan' => 'required|in:Baik,Rusak,Hilang',
        ]);

        $data['used_for'] = 'Umum';
        $data['program_id'] = null;

        if ($request->hasFile('loan_letter')) {
            $path = $request->file('loan_letter')->store('loan_letters', 'public');
            $data['loan_letter'] = $path;
        }

        $data['inventory_id'] = $id;
        $data['member_id'] = null;
        $data['status'] = 'Dipinjam';

        InventoryLoan::create($data);

        return back()->with('success', 'Peminjaman inventaris berhasil dicatat.');
    }

    public function returnLoan(Request $request, $loanId)
    {
        $loan = InventoryLoan::findOrFail($loanId);
        $request->validate([
            'actual_return_date' => 'required|date',
            'condition_on_return' => 'required|in:Baik,Rusak,Hilang',
        ]);

        $loan->update([
            'actual_return_date' => $request->actual_return_date,
            'condition_on_return' => $request->condition_on_return,
            'status' => 'Dikembalikan',
        ]);

        return back()->with('success', 'Pengembalian inventaris berhasil dicatat.');
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return back()->with('success', 'Aset inventaris berhasil dihapus.');
    }
}