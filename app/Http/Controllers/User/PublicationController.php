<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use App\Models\EventData;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PublicationController extends Controller
{
    /**
     * Tampilkan halaman Schedules & Results (dua tab dalam satu view),
     * sekaligus data dokumen Syarat & Ketentuan untuk tombol download dan register aktif.
     */
    public function scheduleResult(Request $request)
    {
        // ——— SCHEDULE — dari tabel match_data —————————
        $schedules = DB::table('match_data')
            ->select('id', 'upload_date', 'main_title', 'layout_image')
            ->where('status', 'publish')
            ->orderBy('upload_date', 'asc')
            ->get();

        // ——— RESULTS — dari tabel match_results ————————
        $today = Carbon::today();
        $resultsQuery = MatchResult::with(['team1','team2'])
            ->whereNotNull('score_1')
            ->whereNotNull('score_2')
            ->whereDate('match_date', '<', $today)
            ->orderBy('match_date', 'desc');

        // Pagination manual
        $perPage = 10;
        $page    = LengthAwarePaginator::resolveCurrentPage();
        $items   = $resultsQuery->get();
        $slice   = $items->slice(($page - 1) * $perPage, $perPage)->values();

        $results = new LengthAwarePaginator(
            $slice,
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ——— TERM & CONDITIONS — ambil semua dokumen ————
        $terms = TermCondition::orderBy('year', 'desc')->get();

        // ——— CEK ADA EVENT YANG AKTIF ———
        $now = Carbon::today();
        $hasActive = EventData::whereDate('start_date', '<=', $now)
                              ->whereDate('end_date', '>=', $now)
                              ->exists();

        return view('user.publication.schedule_result', [
            'schedules'  => $schedules,
            'results'    => $results,
            'terms'      => $terms,
            'hasActive'  => $hasActive,
        ]);
    }

    /**
     * Download dokumen S&K:
     * - Jika hanya 1 file, langsung download PDF
     * - Jika >1 file, gabung ke ZIP
     */
    public function downloadTerms()
    {
        $files = TermCondition::pluck('file_path')->all();

        if (empty($files)) {
            return redirect()->back()->with('error', 'Tidak ada dokumen S&K untuk diunduh.');
        }

        if (count($files) === 1) {
            return Storage::disk('public')->download($files[0]);
        }

        // ZIP Multiple Files
        $zipName = 'syarat_ketentuan.zip';
        $zipPath = storage_path('app/public/' . $zipName);

        if (file_exists($zipPath)) {
            @unlink($zipPath);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $absolute = storage_path('app/public/' . $file);
                if (file_exists($absolute)) {
                    $zip->addFile($absolute, basename($file));
                }
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
