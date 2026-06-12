<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\FinanceCategory;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Finance::with('category');

        if ($request->filled('category_id')) {
            $query->where('finance_category_id', $request->category_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $finances = $query->orderBy('transaction_date', 'desc')->paginate(10);
        $categories = FinanceCategory::all();

        // Calculate global net balance (all transactions)
        $totalIncome = Finance::where('type', 'income')->sum('amount');
        $totalExpense = Finance::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('pengurus.finances.index', compact('finances', 'categories', 'netBalance'));
    }

    public function create()
    {
        $categories = FinanceCategory::all();
        $programs = \App\Models\Program::active()->get();
        return view('pengurus.finances.create', compact('categories', 'programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'used_for' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:used_for,Program Kerja|nullable|exists:programs,id',
        ]);

        $category = FinanceCategory::findOrFail($request->finance_category_id);
        $data['type'] = $category->type;

        if ($data['used_for'] === 'Umum') {
            $data['program_id'] = null;
        }

        if ($request->hasFile('receipt_file')) {
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $data['receipt_file'] = $path;
        }

        Finance::create($data);

        return redirect()->route('pengurus.finances.index')->with('success', 'Transaksi keuangan berhasil dicatat.');
    }

    public function edit($id)
    {
        $finance = Finance::findOrFail($id);
        $categories = FinanceCategory::all();
        $programs = \App\Models\Program::active()->get();
        return view('pengurus.finances.edit', compact('finance', 'categories', 'programs'));
    }

    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        $data = $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'used_for' => 'required|in:Umum,Program Kerja',
            'program_id' => 'required_if:used_for,Program Kerja|nullable|exists:programs,id',
        ]);

        $category = FinanceCategory::findOrFail($request->finance_category_id);
        $data['type'] = $category->type;

        if ($data['used_for'] === 'Umum') {
            $data['program_id'] = null;
        }

        if ($request->hasFile('receipt_file')) {
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $data['receipt_file'] = $path;
        }

        $finance->update($data);

        return redirect()->route('pengurus.finances.index')->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:finance_categories,name',
            'type' => 'required|in:income,expense',
        ]);

        FinanceCategory::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Kategori keuangan berhasil ditambahkan.');
    }

    public function destroyCategory($id)
    {
        $category = FinanceCategory::findOrFail($id);
        if ($category->finances()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah memiliki data transaksi.');
        }
        $category->delete();
        return back()->with('success', 'Kategori keuangan berhasil dihapus.');
    }

    public function destroy($id)
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();
        return back()->with('success', 'Transaksi keuangan berhasil dihapus.');
    }
}