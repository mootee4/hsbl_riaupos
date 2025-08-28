<?php
namespace App\Http\Controllers\Publication;

use App\Http\Controllers\Controller;
use App\Models\AddData;
use App\Models\EventData;
use App\Models\MatchResult;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    // ====================
    // MATCH SCHEDULE (ADMIN)
    // ====================

    /**
     * Menampilkan daftar match schedule dengan optional filter status dan pagination
     */
    public function match(Request $request)
    {
        $status       = $request->get('status');
        $seriesFilter = $request->get('series_name');
        $yearFilter   = $request->get('filter_year');
        $search       = $request->get('search');
        $perPage      = $request->get('per_page', 10);
        $sort         = $request->get('sort', 'desc'); // default: terbaru duluan

        // Series statis sesuai ketentuan
        $allSeries = collect([
            'Bengkalis Series',
            'Indragiri Hilir Series',
            'Indragiri Hulu Series',
            'Kampar Series',
            'Kepulauan Meranti Series',
            'Kuantan Singingi Series',
            'Pelalawan Series',
            'Rokan Hilir Series',
            'Rokan Hulu Series',
            'Siak Series',
            'Dumai Series',
            'Pekanbaru Series',
        ]);

        // Query utama
        $matches = DB::table('match_data')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($seriesFilter, function ($query) use ($seriesFilter) {
                return $query->where('series_name', $seriesFilter);
            })
            ->when($yearFilter, function ($query) use ($yearFilter) {
                return $query->whereYear('upload_date', $yearFilter);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('main_title', 'like', '%' . $search . '%');
            })
            ->orderBy('upload_date', $sort)
            ->paginate($perPage)
            ->withQueryString();

        // Tahun tersedia
        $availableYears = DB::table('match_data')
            ->selectRaw('YEAR(upload_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('publication.pub_schedule', [
            'matches'        => $matches,
            'status'         => $status,
            'perPage'        => $perPage,
            'availableYears' => $availableYears,
            'allSeries'      => $allSeries,
            'seriesFilter'   => $seriesFilter,
            'sort'           => $sort,
        ]);
    }

    /**
     * Simpan data match schedule baru dengan validasi dan upload gambar layout
     */
    public function storeMatch(Request $request)
    {
        // Validasi input + foto max 10 MB
        $request->validate([
            'upload_date'  => 'required|date',
            'main_title'   => 'required|string|max:255',
            'caption'      => 'nullable|string',
            'layout_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10 MB
            'status'       => 'required|in:draft,publish',
            'series_name'  => 'required|string',
        ]);

        $layoutImageName = null;

        if ($request->hasFile('layout_image')) {
            $file       = $request->file('layout_image');
            $filename   = time() . '_' . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $folderPath = public_path('images/schedule');

            // Buat folder jika belum ada
            if (! File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Pindahkan file
            $file->move($folderPath, $filename);

            // Path relatif untuk disimpan ke DB
            $layoutImageName = 'images/schedule/' . $filename;
        }

        // Insert ke table match_data
        DB::table('match_data')->insert([
            'upload_date'  => $request->upload_date,
            'main_title'   => $request->main_title,
            'caption'      => $request->caption,
            'layout_image' => $layoutImageName,
            'status'       => $request->status,
            'series_name'  => $request->series_name,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Match schedule berhasil disimpan.');
    }

    /**
     * Publish match schedule (ubah status menjadi 'publish')
     */
    public function publish($id)
    {
        DB::table('match_data')
            ->where('id', $id)
            ->update(['status' => 'publish', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Match berhasil dipublish!');
    }

    /**
     * Unpublish match schedule (ubah status menjadi 'draft')
     */
    public function unpublish($id)
    {
        DB::table('match_data')
            ->where('id', $id)
            ->update(['status' => 'draft', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Match berhasil di-unpublish!');
    }

    /**
     * Tandai match schedule sebagai done
     */
    public function done($id)
    {
        DB::table('match_data')
            ->where('id', $id)
            ->update(['status' => 'done', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Match ditandai selesai!');
    }

    // ====================
    // MATCH RESULT
    // ====================

    // Menampilkan daftar hasil pertandingan
    public function result(Request $request)
    {
        $matchResults = MatchResult::with(['team1', 'team2', 'phaseData', 'competitionTypeData'])
            ->orderBy('match_date', 'desc')
            ->paginate(10);

        $schools = School::all();

        $phases = AddData::whereNotNull('phase')->select('phase')->distinct()->pluck('phase');

        $competitionTypes = AddData::whereNotNull('competition_type')->select('competition_type')->distinct()->pluck('competition_type');

        $competitions = AddData::whereNotNull('competition')->select('competition')->distinct()->pluck('competition');

        return view('publication.pub_result', compact('matchResults', 'schools', 'phases', 'competitionTypes', 'competitions'));
    }
    public function storeResult(Request $request)
    {
        $validated = $request->validate([
            'date'             => 'required|date',
            'competition'      => 'required|string|max:255',
            'competition_type' => 'required|string|max:255',
            'phase'            => 'required|string|max:255',
            'team1_id'         => 'required|exists:schools,id',
            'team2_id'         => 'required|exists:schools,id|different:team1_id',
            'score_team1'      => 'required|integer|min:0',
            'score_team2'      => 'required|integer|min:0',
            'scoresheet'       => 'nullable|file|mimes:pdf,xlsx,docx|max:2048',
        ]);

        $scoresheetPath = null;
        if ($request->hasFile('scoresheet')) {
            $scoresheetPath = $request->file('scoresheet')->store('match_results', 'public');
        }

        // Ambil ID dari AddData berdasarkan phase dan competition_type
        $phaseData           = AddData::where('phase', $validated['phase'])->first();
        $competitionTypeData = AddData::where('competition_type', $validated['competition_type'])->first();

        MatchResult::create([
            'match_date'       => $validated['date'],
            'competition'      => $validated['competition'],
            'competition_type' => $validated['competition_type'], // tambahkan ini
            'phase'            => $validated['phase'],
            'team1_id'         => $validated['team1_id'],
            'team2_id'         => $validated['team2_id'],
            'score_1'          => $validated['score_team1'],
            'score_2'          => $validated['score_team2'],
            'scoresheet'       => $scoresheetPath,
        ]);

        return redirect()->route('admin.pub_result')->with('success', 'Hasil pertandingan berhasil disimpan!');
    }
    public function updateResult(Request $request, $id)
    {
        $table = 'match_results';

        // Validasi semua input
        $request->validate([
            'competition'      => 'required|string|max:255',
            'competition_type' => 'required|string|max:255',
            'phase'            => 'required|string|max:255',
            'team1_id'         => 'required|exists:schools,id',
            'team2_id'         => 'required|exists:schools,id|different:team1_id',
            'score_1'          => 'required|integer|min:0',
            'score_2'          => 'required|integer|min:0',
            'match_date'       => 'required|date',
            'scoresheet'       => 'nullable|file|mimes:pdf,xlsx,xls|max:5120',
        ]);

        // Data yang akan disimpan ke database
        $data = [
            'competition'      => $request->input('competition'),
            'competition_type' => $request->input('competition_type'),
            'phase'            => $request->input('phase'),
            'team1_id'         => $request->input('team1_id'),
            'team2_id'         => $request->input('team2_id'),
            'score_1'          => $request->input('score_1'),
            'score_2'          => $request->input('score_2'),
            'match_date'       => $request->input('match_date'),
        ];

        // Handle file upload baru (jika ada)
        if ($request->hasFile('scoresheet')) {
            $oldFile = DB::table($table)->where('id', $id)->value('scoresheet');

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $filePath           = $request->file('scoresheet')->store('match_results', 'public');
            $data['scoresheet'] = $filePath;
        }

        // Update data di database
        DB::table($table)->where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Data hasil pertandingan berhasil diperbarui.');
    }

// ====================
// Event
// ====================

public function event(Request $request)
{
    $now     = Carbon::now('Asia/Jakarta');
    $perPage = (int) $request->get('per_page', 3);
    $perPage = $perPage > 0 ? $perPage : 3; // fallback jika 0 atau tidak valid

    $query = EventData::query();

    // Filter by search keyword
    if ($request->filled('search')) {
        $query->where('event_name', 'like', '%' . $request->search . '%');
    }

    // Filter by month
    if ($request->filled('filter_month')) {
        $query->whereMonth('start_date', $request->filter_month);
    }

    // Filter by year
    if ($request->filled('filter_year')) {
        $query->whereYear('start_date', $request->filter_year);
    }

    // Filter by specific event ID
    if ($request->filled('filter_event')) {
        $query->where('id', $request->filter_event);
    }

    // Filter by status
    if ($request->filled('status')) {
        $status = $request->status;

        // Ambil semua data terlebih dahulu untuk filtering manual
        $query = $query->get()->filter(function ($event) use ($status, $now) {
            $startTime = $event->start_time ?? '00:00:00';
            $endTime   = $event->end_time ?? '23:59:59';

            $start = Carbon::parse($event->start_date . ' ' . $startTime, 'Asia/Jakarta');
            $end   = Carbon::parse($event->end_date . ' ' . $endTime, 'Asia/Jakarta');

            return match ($status) {
                'scheduled' => $now->lt($start),
                'published' => $now->between($start, $end),
                'done'      => $now->gt($end),
                default     => true,
            };
        });

        // Konversi ulang ke paginator manual karena hasilnya jadi collection
        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $query->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $allEvents    = new LengthAwarePaginator(
            $currentItems,
            $query->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    } else {
        // Jika tidak pakai filter status, lanjutkan query builder biasa
        $sortOrder = in_array($request->get('sort'), ['asc', 'desc']) ? $request->get('sort') : 'desc';

        $allEvents = $query->orderBy('created_at', $sortOrder)
            ->paginate($perPage)
            ->withQueryString();
    }

    // Untuk menandai event aktif
    $activeEvents = $allEvents->filter(function ($event) use ($now) {
        $startTime = $event->start_time ?? '00:00:00';
        $endTime   = $event->end_time ?? '23:59:59';

        $start = Carbon::parse($event->start_date . ' ' . $startTime, 'Asia/Jakarta');
        $end   = Carbon::parse($event->end_date . ' ' . $endTime, 'Asia/Jakarta');

        return $now->between($start, $end);
    });

    $availableYears = EventData::selectRaw('YEAR(start_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

    return view('publication.pub_event', [
        'activeEvents'   => $activeEvents,
        'allEvents'      => $allEvents,
        'availableYears' => $availableYears,
        'perPage'        => $perPage,
    ]);
}

public function storeEvent(Request $request)
{
    $request->validate([
        'event_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'start_time' => 'required|date_format:H:i',
        'end_time'   => 'required|date_format:H:i',
        'term_cond'  => 'nullable|file|mimes:pdf|max:2048', // SnK PDF file
    ]);

    $timezone = config('app.timezone');

    $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->start_date . ' ' . $request->start_time, $timezone);
    $endDateTime   = Carbon::createFromFormat('Y-m-d H:i', $request->end_date . ' ' . $request->end_time, $timezone);

    $termCondPath = null;
    if ($request->hasFile('term_cond')) {
        $termCondPath = $request->file('term_cond')->store('term_cond', 'public');
    }

    EventData::create([
        'event_name' => $request->event_name,
        'start_date' => $startDateTime->toDateString(),
        'end_date'   => $endDateTime->toDateString(),
        'start_time' => $startDateTime->copy()->setTimezone('UTC')->format('H:i:s'),
        'end_time'   => $endDateTime->copy()->setTimezone('UTC')->format('H:i:s'),
        'term_cond'  => $termCondPath,
    ]);

    return redirect()->back()->with('success', 'Event successfully created.');
}
}