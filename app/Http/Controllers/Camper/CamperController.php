<?php

namespace App\Http\Controllers\Camper;

use Illuminate\Http\Request;
use App\Models\CamperList;
use App\Http\Controllers\Controller;

class CamperController extends Controller
{
    // Tampilkan semua camper
    public function camper()
    {
        // Ambil camper + relasi player → school & season
        $campers = CamperList::with([
            'player.schoolData',
            'player.team',
            'season'
        ])->get();

        return view('camper.camper_team', compact('campers'));
    }

    // Detail berdasarkan player_id
    public function camperDetail($id)
    {
        $camper = CamperList::with([
            'player.schoolData',
            'season'
        ])->where('player_id', $id)->firstOrFail();

        return view('camper.camper_detail', compact('camper'));
    }
    public function updateCamper(Request $request, $id)
    {
        $request->validate([
            'gender' => 'in:Laki-laki,Perempuan',
            'birth_date' => 'date|nullable',
            'nik' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:100',
            // ... validasi lain opsional
        ]);

        $player = \App\Models\PlayerList::findOrFail($id);

        $player->update([
            'nik' => $request->nik,
            'birthdate' => $request->birth_date, // map ke kolom 'birthdate'
            'gender' => $request->gender,
            'phone' => $request->phone,
            'grade' => $request->grade,
            'height' => $request->height,
            'weight' => $request->weight,
            'tshirt_size' => $request->tshirt_size,
            'shoes_size' => $request->shoes_size,
            'basketball_position' => $request->basketball_position,
        ]);
        return redirect()->route('admin.camper.camper_detail', $id)->with('success', 'Data berhasil diperbarui!');
    }
}
