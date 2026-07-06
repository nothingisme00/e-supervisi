<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PredikatRubrik;
use App\Models\RubrikItem;
use Illuminate\Http\Request;

class RubrikItemController extends Controller
{
    public function index()
    {
        $itemsBySection = RubrikItem::orderBy('urutan')->get()->groupBy('section');
        $predikatList = PredikatRubrik::orderByDesc('batas_minimal')->get();

        return view('admin.rubrik-items.index', compact('itemsBySection', 'predikatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:rubrik_items,kode',
            'section' => 'required|in:A,B,C',
            'section_label' => 'required|string|max:255',
            'kelompok_nomor' => 'required|integer|min:1',
            'kelompok_label' => 'required|string|max:255',
            'sub_label' => 'required|string|max:1000',
            'urutan' => 'required|integer|min:1',
        ]);

        RubrikItem::create($validated + ['is_active' => true]);

        return redirect()->route('admin.rubrik-items.index')
            ->with('success', 'Item rubrik berhasil ditambahkan.');
    }

    public function toggle(RubrikItem $rubrikItem)
    {
        $rubrikItem->update(['is_active' => !$rubrikItem->is_active]);

        return redirect()->route('admin.rubrik-items.index')
            ->with('success', 'Status item rubrik berhasil diubah.');
    }

    public function updatePredikat(Request $request, PredikatRubrik $predikatRubrik)
    {
        $validated = $request->validate([
            'batas_minimal' => 'required|numeric|min:0|max:100',
        ]);

        $predikatRubrik->update($validated);

        return redirect()->route('admin.rubrik-items.index')
            ->with('success', 'Ambang predikat berhasil diperbarui.');
    }
}
