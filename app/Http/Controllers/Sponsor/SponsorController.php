<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SponsorController extends Controller
{
    public function sponsor(Request $request)
    {
        $query = Sponsor::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('sponsor_name', 'like', "%{$request->search}%");
        }

        // **Ubah urutan ke ascending agar sponsor baru di BAWAH**
        $sponsors = $query->orderBy('created_at', 'asc')->get();

        $categories = [
            'Presented By','Official Partners',
            'Official Suppliers','Supporting Partners','Managed By'
        ];

        return view('admin.sponsor.sponsor', compact('sponsors', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sponsor_name'=>'required|string|max:255',
            'category'=>'required|string|max:255',
            'logo'=>'required|image|mimes:jpg,jpeg,png,svg|max:2048',
            'sponsors_web'=>'nullable|url|max:255',
        ]);

        $folder = public_path('uploads/sponsors');
        if (! File::exists($folder)) {
            File::makeDirectory($folder, 0755, true);
        }

        $file     = $request->file('logo');
        $filename = time() . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $filename);

        $data['logo'] = $filename;
        Sponsor::create($data);

        return redirect()->route('admin.sponsor.sponsor')
                         ->with('success','Sponsor berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $data = $request->validate([
            'sponsor_name'=>'required|string|max:255',
            'category'=>'required|string|max:255',
            'logo'=>'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'sponsors_web'=>'nullable|url|max:255',
        ]);

        $folder = public_path('uploads/sponsors');
        if ($request->hasFile('logo')) {
            if (! File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
            // hapus file lama
            $old = $folder . '/' . $sponsor->logo;
            if (File::exists($old)) {
                File::delete($old);
            }
            $file     = $request->file('logo');
            $filename = time() . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($folder, $filename);
            $data['logo'] = $filename;
        }

        $sponsor->update($data);

        return redirect()->route('admin.sponsor.sponsor')
                         ->with('success','Sponsor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $file = public_path('uploads/sponsors/' . $sponsor->logo);
        if (File::exists($file)) {
            File::delete($file);
        }
        $sponsor->delete();

        return redirect()->route('admin.sponsor.sponsor')
                         ->with('success','Sponsor berhasil dihapus.');
    }

    public function destroySelected(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids)) {
            $items = Sponsor::whereIn('id', $ids)->get();
            $folder = public_path('uploads/sponsors');
            foreach ($items as $s) {
                $file = $folder . '/' . $s->logo;
                if (File::exists($file)) {
                    File::delete($file);
                }
                $s->delete();
            }
            return redirect()->route('admin.sponsor.sponsor')
                             ->with('success','Sponsor terpilih berhasil dihapus.');
        }
        return redirect()->route('admin.sponsor.sponsor')
                         ->with('error','Tidak ada sponsor dipilih.');
    }
}
