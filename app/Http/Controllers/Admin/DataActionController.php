<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DataActionController extends Controller
{
    private $map = [
        'school' => [
            'table' => 'schools',
            'cols'  => ['id', 'school_name', 'city_id', 'category_name', 'type'],
        ],
        'award' => [
            'table' => 'awards',
            'cols'  => ['id', 'award_type', 'category'],
        ],
        'venue' => [
            'table' => 'venue',
            'cols'  => ['id', 'venue_name', 'city_id', 'location', 'layout'],
        ],
        'match' => [
            'table' => 'match_data',
            'cols'  => ['id', 'upload_date', 'main_title', 'caption', 'layout_image', 'status'],
        ],
        'match_result' => [
            'table' => 'match_results',
            'cols'  => ['id', 'match_id', 'team1_score', 'team2_score', 'match_date', 'status'],
        ],
        'camper' => [
            'table' => 'camper',
            'cols'  => ['id', 'camper_name', 'school_id'],
        ],
        'city' => [
            'table' => 'cities',
            'cols'  => ['id', 'city_name'],
        ],
        'event' => [
            'table' => 'events_data',
            'cols'  => ['id', 'event_name', 'start_date', 'start_time', 'end_date', 'end_time'],
        ],

    ];

    public function index(Request $request, string $type)
    {
        if (!isset($this->map[$type])) {
            abort(404, 'Tipe data tidak valid.');
        }

        $table  = $this->map[$type]['table'];
        $cols   = $this->map[$type]['cols'];
        $search = $request->input('search');

        $query = DB::table($table);
        if ($search) {
            $query->where($cols[1], 'like', "%{$search}%");
        }

        $rows = $query->orderBy($cols[0])->get();

        return view("admin.all_data_{$type}", [
            'rows'   => $rows,
            'type'   => $type,
            'search' => $search,
        ]);
    }

    public function edit(Request $request)
    {
        $table = $request->input('table');
        $id    = $request->input('id');

        $data  = $request->except([
            '_token',
            'table',
            'id',
            'layout',
            'layout_image',
            'scoresheet',
            'type',
            'original_value',
            'new_value',
            'old_value'
        ]);

        // --- ADD_DATA special logic
        if ($table === 'add_data') {
            $type = $request->input('type');
            $old  = $request->input('old_value');
            $new  = $request->input('new_value');

            $colMap = [
                'season'           => 'season_name',
                'series'           => 'series_name',
                'competition'      => 'competition',
                'phase'            => 'phase',
                'competition type' => 'competition_type',
            ];

            if (!isset($colMap[$type])) {
                return redirect()->back()->with('error', 'Tipe add_data tidak valid.');
            }

            DB::table('add_data')->where($colMap[$type], $old)->update([$colMap[$type] => $new]);
            return redirect()->back()->with('success', 'Data add_data berhasil diupdate.');
        }

        // --- AWARD update by type
        if ($table === 'awards' && $request->filled(['type', 'original_value', 'new_value'])) {
            $type = $request->input('type');
            $old  = $request->input('original_value');
            $new  = $request->input('new_value');

            if (!in_array($type, ['award_type', 'category'])) {
                return redirect()->back()->with('error', 'Kolom award tidak valid.');
            }

            DB::table('awards')->where($type, $old)->update([$type => $new]);
            return redirect()->back()->with('success', 'Data award berhasil diupdate.');
        }

        // --- Translate table alias to real name
        if (array_key_exists($table, $this->map)) {
            $table = $this->map[$table]['table'];
        }
        // --- VENUE layout upload
        if ($table === 'venue') {
            if ($request->hasFile('layout')) {
                $oldLayout = DB::table($table)->where('id', $id)->value('layout');
                if ($oldLayout && Storage::disk('public')->exists($oldLayout)) {
                    Storage::disk('public')->delete($oldLayout);
                }

                $layoutPath = $request->file('layout')->store('venue_layouts', 'public');
                $data['layout'] = $layoutPath;
            }

            DB::table($table)->where('id', $id)->update($data);
            return redirect()->back()->with('success', 'Venue berhasil diupdate.');
        }

        // --- MATCH DATA
        if ($table === 'match_data') {
            $match = DB::table($table)->where('id', $id)->first();
            if ($match && strtolower($match->status) === 'done') {
                return redirect()->back()->with('warning', 'Jadwal yang sudah selesai tidak dapat diubah.');
            }
            
        
            if ($request->hasFile('layout_image')) {
                $request->validate([
                    'layout_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
        
                $oldImage = DB::table($table)->where('id', $id)->value('layout_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
        
                $imagePath = $request->file('layout_image')->store('match_layouts', 'public');
                $data['layout_image'] = $imagePath;
        
                DB::table($table)->where('id', $id)->update($data);
                return redirect()->back()->with('success', 'Match data berhasil diupdate.');
            }
        }
        

        // --- MATCH_RESULTS
        if ($table === 'match_results') {
            if (isset($data['date'])) {
                $data['match_date'] = $data['date'];
                unset($data['date']);
            }

            if ($request->hasFile('scoresheet')) {
                $oldFile = DB::table($table)->where('id', $id)->value('scoresheet');
                if ($oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }

                $filePath = $request->file('scoresheet')->store('match_results', 'public');
                $data['scoresheet'] = $filePath;
            }

            DB::table($table)->where('id', $id)->update($data);
            return redirect()->back()->with('success', 'Hasil pertandingan berhasil diupdate.');
        }

        // --- EVENT edit (update tanggal dan jam)
        if ($table === 'events_data') {
            $updateData = $data;

            if ($request->has('start_time')) {
                // Asumsikan input start_time format 'H:i' atau 'H:i:s' dalam timezone lokal, misal 'Asia/Jakarta'
                $startTime = Carbon::createFromFormat('H:i', $request->input('start_time'), 'Asia/Jakarta')
                    ->setTimezone('UTC')
                    ->format('H:i:s');
                $updateData['start_time'] = $startTime;
            }
            if ($request->has('end_time')) {
                $endTime = Carbon::createFromFormat('H:i', $request->input('end_time'), 'Asia/Jakarta')
                    ->setTimezone('UTC')
                    ->format('H:i:s');
                $updateData['end_time'] = $endTime;
            }

            DB::table($table)->where('id', $id)->update($updateData);
            return redirect()->back()->with('success', 'Event berhasil diupdate.');
        }


        // --- Default update
        DB::table($table)->where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Data berhasil diupdate.');
    }

    public function delete(Request $request)
    {
        $table  = $request->input('table');
        $id     = $request->input('id');
        $type   = $request->input('type');
        $values = $request->input('selected');
        $field  = $request->input('field');
        $value  = $request->input('value');

        $allowedTables = array_column($this->map, 'table');

        if ($table === 'add_data') {
            $colMap = [
                'season'           => 'season_name',
                'series'           => 'series_name',
                'competition'      => 'competition',
                'phase'            => 'phase',
                'competition type' => 'competition_type',
            ];

            if (isset($colMap[$type]) && is_array($values)) {
                foreach ($values as $val) {
                    DB::table('add_data')->where($colMap[$type], $val)->delete();
                }
                return redirect()->back()->with('success', 'Data add_data berhasil dihapus.');
            } else {
                return redirect()->back()->with('error', 'Tipe add_data tidak valid atau data kosong.');
            }
        }

        if (!empty($field) && !empty($value) && !empty($table)) {
            if (!in_array($table, $allowedTables)) {
                return redirect()->back()->with('error', 'Tabel tidak valid.');
            }

            DB::table($table)->where($field, $value)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        }

        if (!empty($id) && !empty($table)) {
            if (!in_array($table, $allowedTables)) {
                return redirect()->back()->with('error', 'Tabel tidak valid.');
            }

            // Delete file jika ada, khusus beberapa tabel
            if ($table === 'match_data') {
                $filename = DB::table($table)->where('id', $id)->value('layout_image');
                if ($filename) {
                    Storage::disk('public')->delete($filename);
                }
            }

            if ($table === 'venue') {
                $filename = DB::table($table)->where('id', $id)->value('layout');
                if ($filename) {
                    Storage::disk('public')->delete($filename);
                }
            }

            if ($table === 'match_results') {
                $file = DB::table($table)->where('id', $id)->value('scoresheet');
                if ($file) {
                    Storage::disk('public')->delete($file);
                }
            }

            // --- EVENT delete
            if ($table === 'events_data') {
                if (!empty($id)) {
                    DB::table($table)->where('id', $id)->delete();
                    return redirect()->back()->with('success', 'Event berhasil dihapus.');
                } elseif ($request->has('start_time') && $request->has('end_time')) {
                    $startTime = $request->input('start_time');
                    $endTime = $request->input('end_time');
                    DB::table($table)->whereBetween('start_time', [$startTime, $endTime])->delete();
                    return redirect()->back()->with('success', 'Event berdasarkan waktu berhasil dihapus.');
                }
            }



            DB::table($table)->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->back()->withInput()->with('error', 'Parameter tidak lengkap atau tidak valid.');
    }













    /**
     * Export data ke CSV.
     */
    public function export(string $type)
    {
        if (!isset($this->map[$type])) {
            abort(404, 'Tipe data tidak valid.');
        }

        $table = $this->map[$type]['table'];
        $cols  = $this->map[$type]['cols'];

        $data = DB::table($table)->select($cols)->get();

        $filename = "{$type}_data_" . date('Ymd_His') . ".csv";

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $cols); // header

        foreach ($data as $row) {
            fputcsv($handle, (array) $row);
        }

        rewind($handle);
        $contents = stream_get_contents($handle);
        fclose($handle);

        return Response::make($contents, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
