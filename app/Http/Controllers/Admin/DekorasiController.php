<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dekorasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DekorasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.dekorasi.index', [
            'dekorasi' => Dekorasi::paginate(25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dekorasi.form', [
            'type' => 'add'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dekorasi' => 'required',
            'foto_dekorasi' => 'required',
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();

            // Upload file dulu
            if ($request->hasFile('foto_dekorasi')) {
                $file = $request->file('foto_dekorasi');
                $path = "foto_dekorasi";
                $uploadedFilename = "foto-dekorasi-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_dekorasi'] = $uploadedFilename;
            }


            Dekorasi::create($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.dekorasi.index',
                [],
                'success',
                "Berhasil membuat dekorasi baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_dekorasi/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_dekorasi/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dekorasi $dekorasi)
    {
        return view('admin.dekorasi.form', [
            'type' => 'update',
            'dekorasi' => $dekorasi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dekorasi $dekorasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dekorasi $dekorasi)
    {
        $validated = $request->validate([
            'nama_dekorasi' => 'required',
            'foto_dekorasi' => 'nullable',
        ]);
        $uploadedFilename = null;

        try {
            DB::beginTransaction();
            if (isset($validated['foto_dekorasi']) && is_null($validated['foto_dekorasi'])) {
                if (Storage::disk('public')
                    ->exists("foto_dekorasi/{$dekorasi->foto_dekorasi}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_dekorasi/{$dekorasi->foto_dekorasi}");
                }
            }

            // Upload file dulu
            if ($request->hasFile('foto_dekorasi')) {
                if (
                    $dekorasi->foto_dekorasi
                    && Storage::disk('public')->exists("foto_dekorasi/{$dekorasi->foto_dekorasi}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_dekorasi/{$dekorasi->foto_dekorasi}");
                }
                $file = $request->file('foto_dekorasi');
                $path = "foto_dekorasi";
                $uploadedFilename = "foto-dekorasi-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_dekorasi'] = $uploadedFilename;
            }


            $dekorasi->update($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.dekorasi.index',
                [],
                'success',
                "Berhasil mengupdate dekorasi baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_dekorasi/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_dekorasi/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dekorasi $dekorasi)
    {
        if (
            $dekorasi->foto_gaun
            && Storage::disk('public')->exists("foto_gaun/{$dekorasi->foto_gaun}")
        ) {
            Storage::disk('public')
                ->delete("foto_gaun/{$dekorasi->foto_gaun}");
        }

        $dekorasi->delete();
        return $this->backWithAlert('success', 'Berhasil menghapus data dekorasi!');
    }

    public function getData()
    {
        $gaun = Dekorasi::all();
        $gauns = $gaun->map(function ($item) {
            return [
                'value' => $item->id,
                'text' => $item->nama_dekorasi
            ];
        });
        return response()->json($gauns);
    }
}
