<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Tampilkan list video dengan search & filter.
     */
    public function index(Request $request)
    {
        $query = Video::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('type') && in_array($request->type, ['video','live'])) {
            $query->where('type', $request->type);
        }

        $videos = $query->orderBy('created_at','desc')->paginate(10);

        return view('admin.media.gallery.videos_list', compact('videos'));
    }

    /**
     * Tampilkan form tambah video.
     */
    public function create()
    {
        return view('admin.media.gallery.videos_form');
    }

    /**
     * Simpan video baru.
     */
    public function store(VideoRequest $request)
    {
        // Ambil input dasar
        $data = $request->only(['title','youtube_link','description','status','type']);

        // Normalize: ekstrak ID dan simpan sebagai ID saja
        $videoId = $this->extractYouTubeId($data['youtube_link']);
        if (! $videoId) {
            return back()
                ->withInput()
                ->withErrors(['youtube_link' => 'Link YouTube tidak valid.']);
        }
        $data['youtube_link'] = $videoId;

        // Generate Video Code otomatis
        $latest = Video::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $data['video_code'] = 'VID-00'.$nextId;

        // Generate slug dari title
        $data['slug'] = Str::slug($request->title);

        // Upload thumbnail ke public/uploads/thumbnails
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $file      = $request->file('thumbnail');
            $filename  = time() . '_' . $file->getClientOriginalName();
            $destPath  = public_path('uploads/thumbnails');

            if (! File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }

            $file->move($destPath, $filename);
            $data['thumbnail'] = 'uploads/thumbnails/'.$filename;
        }

        Video::create($data);

        return redirect()->route('admin.videos.index')
                         ->with('success','Video berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit video.
     */
    public function edit(Video $video)
    {
        return view('admin.media.gallery.videos_form', compact('video'));
    }

    /**
     * Update data video.
     */
    public function update(VideoRequest $request, Video $video)
    {
        $data = $request->only(['title','youtube_link','description','status','type']);

        // Normalize YouTube link → ID
        $videoId = $this->extractYouTubeId($data['youtube_link']);
        if (! $videoId) {
            return back()
                ->withInput()
                ->withErrors(['youtube_link' => 'Link YouTube tidak valid.']);
        }
        $data['youtube_link'] = $videoId;

        // Update slug
        $data['slug'] = Str::slug($request->title);

        // Handle thumbnail jika ada
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            if ($video->thumbnail && File::exists(public_path($video->thumbnail))) {
                File::delete(public_path($video->thumbnail));
            }
            $file      = $request->file('thumbnail');
            $filename  = time() . '_' . $file->getClientOriginalName();
            $destPath  = public_path('uploads/thumbnails');
            if (! File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $data['thumbnail'] = 'uploads/thumbnails/'.$filename;
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')
                         ->with('success','Video berhasil diperbarui.');
    }

    /**
     * Hapus video beserta thumbnail-nya.
     */
    public function destroy(Video $video)
    {
        if ($video->thumbnail && File::exists(public_path($video->thumbnail))) {
            File::delete(public_path($video->thumbnail));
        }
        $video->delete();

        return back()->with('success','Video berhasil dihapus.');
    }

    /**
     * Helper: Ekstrak YouTube video ID dari berbagai format URL.
     *
     * @param  string  $url
     * @return string|null
     */
    private function extractYouTubeId(string $url): ?string
    {
        // match youtu.be/<id>
        if (preg_match('/youtu\.be\/([^\?\/]+)/', $url, $m)) {
            return $m[1];
        }
        // match youtube.com/watch?v=<id>
        if (preg_match('/v=([^&]+)/', $url, $m)) {
            return $m[1];
        }
        // match youtube.com/embed/<id>
        if (preg_match('/embed\/([^\?\/]+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
