<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Models\EventData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function event()
    {
        $now = Carbon::now('UTC');

        $activeEvents = EventData::whereRaw("
        CONCAT(start_date, ' ', COALESCE(start_time, '00:00:00')) <= ?
        AND
        CONCAT(end_date, ' ', COALESCE(end_time, '23:59:59')) >= ?
    ", [$now, $now])
            ->orderByRaw("CONCAT(end_date, ' ', COALESCE(end_time, '23:59:59')) ASC")
            ->get();

        return view('user.event', [
            'activeEvents' => $activeEvents
        ]);
    }
    // Di app/Http/Controllers/UserController/UserController.php
    public function registerEvent($id)
    {
        // Misalnya ambil data event dari database dan return view
        $event = EventData::findOrFail($id);
        return view('user.event_register', compact('event'));
    }

    public function schedule()
    {
        $publishedMatches = DB::table('match_data')
            ->where('status', 'publish')
            ->orderBy('upload_date', 'asc')
            ->get();

        return view('user.schedule', compact('publishedMatches'));
    }

    public function showSchedule()
    {
        $schedules = DB::table('match_data')
            ->where('status', 'publish')
            ->orderBy('upload_date', 'desc') // kalau mau yg terbaru dulu
            ->get();

        return view('user.schedule', compact('schedules'));
    }
}
