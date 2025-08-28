<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TermConditionController extends Controller
{
    /**
     * Tampilkan semua data dokumen S&K
     */
    public function index()
    {
        $terms = TermCondition::orderBy('year', 'desc')->get();
        return view('admin.term_conditions.index', compact('terms'));
    }

    /**
     * Simpan dokumen baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_name' => 'required|string|max:255',
            'year'       => 'required|digits:4|integer|min:2000|max:' . date('Y'),
            'file'       => 'required|mimes:pdf|max:2048',
        ]);

        // Generate nama file acak
        $filename = Str::random(20) . '.pdf';

        // Simpan file ke disk "public" (bukan default "local")
        $path = $request->file('file')->storeAs('term_cond', $filename, 'public');

        TermCondition::create([
            'event_name' => $data['event_name'],
            'year'       => $data['year'],
            'file_path'  => $path, // Simpan path seperti: term_cond/abc123.pdf
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil di-upload.');
    }

    /**
     * Hapus satu dokumen
     */
    public function destroy($id)
    {
        $term = TermCondition::findOrFail($id);

        // Hapus file dari disk 'public'
        Storage::disk('public')->delete($term->file_path);

        // Hapus record dari database
        $term->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Hapus beberapa dokumen sekaligus
     */
    public function destroySelected(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $terms = TermCondition::whereIn('id', $ids)->get();

        foreach ($terms as $term) {
            Storage::disk('public')->delete($term->file_path);
            $term->delete();
        }

        return redirect()->back()->with('success', 'Dokumen terpilih berhasil dihapus.');
    }
}
