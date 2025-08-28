<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        $query = News::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('title', 'like', "%{$kw}%")
                  ->orWhere('content', 'like', "%{$kw}%")
            );
        }

        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $news = $query->latest()
                      ->paginate(10)
                      ->withQueryString();

        return view('admin.media.news.news_list', compact('news', 'seriesList'));
    }

    public function create()
    {
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        return view('admin.media.news.news_create', compact('seriesList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'series'     => 'required|string|max:100',
            'title'      => 'required|string|max:255',
            'posted_by'  => 'required|string|max:100',
            'status'     => 'required|in:draft,view,archived',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'content'    => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time()
                  . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $name);
            $data['image'] = 'images/news/' . $name;
        }

        News::create($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $news       = News::findOrFail($id);
        $seriesList = News::pluck('series')
                          ->unique()
                          ->sort()
                          ->toArray();

        return view('admin.media.news.news_edit', compact('news', 'seriesList'));
    }

    public function update(Request $request, int $id)
    {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'series'     => 'required|string|max:100',
            'title'      => 'required|string|max:255',
            'posted_by'  => 'required|string|max:100',
            'status'     => 'required|in:draft,view,archived',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'content'    => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time()
                  . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                  . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news'), $name);
            $data['image'] = 'images/news/' . $name;
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
