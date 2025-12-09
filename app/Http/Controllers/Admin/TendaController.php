<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tenda.index', [
            'tenda' => Tenda::paginate(25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenda.form', [
            'type' => 'add'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tenda' => 'required',
            'foto_tenda' => 'required',
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();

            // Upload file dulu
            if ($request->hasFile('foto_tenda')) {
                $file = $request->file('foto_tenda');
                $path = "foto_tenda";
                $uploadedFilename = "foto-tenda-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_tenda'] = $uploadedFilename;
            }


            Tenda::create($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.tenda.index',
                [],
                'success',
                "Berhasil membuat tenda baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_tenda/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_tenda/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenda $tenda)
    {
        return view('admin.tenda.form', [
            'type' => 'update',
            'tenda' => $tenda
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenda $tenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenda $tenda)
    {
        $validated = $request->validate([
            'nama_tenda' => 'required',
            'foto_tenda' => 'nullable',
        ]);
        $uploadedFilename = null;

        try {
            DB::beginTransaction();
            if (isset($validated['foto_tenda']) && is_null($validated['foto_tenda'])) {
                if (Storage::disk('public')
                    ->exists("foto_tenda/{$tenda->foto_tenda}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_tenda/{$tenda->foto_tenda}");
                }
            }

            // Upload file dulu
            if ($request->hasFile('foto_tenda')) {
                if (
                    $tenda->foto_tenda
                    && Storage::disk('public')->exists("foto_tenda/{$tenda->foto_tenda}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_tenda/{$tenda->foto_tenda}");
                }
                $file = $request->file('foto_tenda');
                $path = "foto_tenda";
                $uploadedFilename = "foto-tenda-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_tenda'] = $uploadedFilename;
            }


            $tenda->update($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.tenda.index',
                [],
                'success',
                "Berhasil mengupdate tenda baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_tenda/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_tenda/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenda $tenda)
    {
        if (
            $tenda->foto_tenda
            && Storage::disk('public')->exists("foto_tenda/{$tenda->foto_tenda}")
        ) {
            Storage::disk('public')
                ->delete("foto_tenda/{$tenda->foto_tenda}");
        }

        $tenda->delete();
        return $this->backWithAlert('success', 'Berhasil menghapus data tenda!');
    }

    public function getData()
    {
        $gaun = Tenda::all();
        $gauns = $gaun->map(function ($item) {
            return [
                'value' => $item->id,
                'text' => $item->nama_tenda
            ];
        });
        return response()->json($gauns);
    }
}
