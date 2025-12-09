<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaunPengantin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GaunPengantinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.gaun.index', [
            'gauns' => GaunPengantin::paginate(25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gaun.form', [
            'type' => 'add'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gaun' => 'required',
            'foto_gaun' => 'required',
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();

            // Upload file dulu
            if ($request->hasFile('foto_gaun')) {
                $file = $request->file('foto_gaun');
                $path = "foto_gaun";
                $uploadedFilename = "foto-gaun-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_gaun'] = $uploadedFilename;
            }


            GaunPengantin::create($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.gaun-pengantin.index',
                [],
                'success',
                "Berhasil membuat gaun pengantin baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_gaun/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_gaun/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GaunPengantin $gaunPengantin)
    {
        return view('admin.gaun.form', [
            'type' => 'update',
            'gaun' => $gaunPengantin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GaunPengantin $gaunPengantin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GaunPengantin $gaunPengantin)
    {
        $validated = $request->validate([
            'nama_gaun' => 'required',
            'foto_gaun' => 'nullable',
        ]);
        $uploadedFilename = null;

        try {
            DB::beginTransaction();
            if (isset($validated['foto_gaun']) && is_null($validated['foto_gaun'])) {
                if (Storage::disk('public')
                    ->exists("foto_gaun/{$gaunPengantin->foto_gaun}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_gaun/{$gaunPengantin->foto_gaun}");
                }
            }

            // Upload file dulu
            if ($request->hasFile('foto_gaun')) {
                if (
                    $gaunPengantin->foto_gaun
                    && Storage::disk('public')->exists("foto_gaun/{$gaunPengantin->foto_gaun}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_gaun/{$gaunPengantin->foto_gaun}");
                }
                $file = $request->file('foto_gaun');
                $path = "foto_gaun";
                $uploadedFilename = "foto-gaun-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_gaun'] = $uploadedFilename;
            }


            $gaunPengantin->update($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.gaun-pengantin.index',
                [],
                'success',
                "Berhasil mengupdate gaun pengantin baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_gaun/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_gaun/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GaunPengantin $gaunPengantin)
    {
        if (
            $gaunPengantin->foto_gaun
            && Storage::disk('public')->exists("foto_gaun/{$gaunPengantin->foto_gaun}")
        ) {
            Storage::disk('public')
                ->delete("foto_gaun/{$gaunPengantin->foto_gaun}");
        }

        $gaunPengantin->delete();
        return $this->backWithAlert('success', 'Berhasil menghapus data gaun pengantin!');
    }

    public function getData()
    {
        $gaun = GaunPengantin::all();
        $gauns = $gaun->map(function ($item) {
            return [
                'value' => $item->id,
                'text' => $item->nama_gaun
            ];
        });
        return response()->json($gauns);
    }
}
