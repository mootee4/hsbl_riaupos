<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 berita terbaru
        $news = News::orderBy('updated_at', 'desc')->limit(8)->get();

        // Ambil dan group sponsor berdasarkan kategori
        $groupedSponsors = Sponsor::orderBy('category')
                                  ->get()
                                  ->groupBy('category');

        // Tampilkan view user.home_01 dengan data
        return view('user.dashboard', [
            'news' => $news,
            'groupedSponsors' => $groupedSponsors,
        ]);
    }
}
