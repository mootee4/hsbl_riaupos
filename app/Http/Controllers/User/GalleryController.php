<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Tampilkan daftar video berdasarkan filter & pencarian.
     */
    public function videos(Request $request)
    {
        $query = Video::query();

        // Filter berdasarkan tipe: video atau live
        if ($request->has('type') && in_array($request->type, ['video', 'live'])) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan pencarian judul
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Hanya tampilkan yang status-nya 'view'
        $videos = $query->where('status', 'view')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        // ✅ Panggil file videos_list.blade.php
        return view('user.media.gallery.videos_list', compact('videos'));
    }

    /**
     * Tampilkan detail video berdasarkan slug.
     */
    public function videoDetail($slug)
    {
        $video = Video::where('slug', $slug)
            ->where('status', 'view')
            ->firstOrFail();

        $others = Video::where('id', '!=', $video->id)
            ->where('status', 'view')
            ->latest()
            ->limit(5)
            ->get();

        return view('user.media.gallery.video_detail', compact('video', 'others'));
    }
}