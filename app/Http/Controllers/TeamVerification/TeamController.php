<?php

namespace App\Http\Controllers\TeamVerification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\School;

class TeamController extends Controller
{
    public function teamList()
    {
        // Ambil semua tim beserta relasi sekolah
        $teamList = TeamList::with('leader')->get();

        return view('team_verification.tv_team_list', compact('teamList'));
    }

    public function teamShow($id)
    {
        $team = TeamList::with(['leader', 'players', 'officials', 'school'])->findOrFail($id);
        return view('team_verification.tv_team_detail', compact('team'));
    }
    




    public function teamVerification()
    {
        // Ambil tim yang belum diverifikasi
        $unverifiedTeams = TeamList::where('verification_status', 'Unverified')->get();

        return view('team_verification.tv_team_verification', compact('unverifiedTeams'));
    }

    public function teamAwards()
    {
        // Placeholder: Bisa diisi logic untuk tim dengan award
        return view('team_verification.tv_team_awards');
    }

    // Tambah data team (form)
    public function create()
    {
        $schools = School::all(); // untuk dropdown sekolah
        return view('team_verification.tv_team_create', compact('schools'));
    }

    // Simpan data team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required',
            'referral_code' => 'required',
            'season' => 'required',
            'series' => 'required',
            'competition' => 'required',
            'team_category' => 'required|in:Boys,Girls,Dancers',
            'registered_by' => 'required',
        ]);


        TeamList::create($validated);

        return redirect()->route('team.list')->with('success', 'Team berhasil ditambahkan!');
    }
}
