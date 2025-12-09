<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortofolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.portofolio.index', [
            'portofolio' => Portofolio::paginate(25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.portofolio.form', [
            'type' => 'add'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_portofolio' => 'required',
            'deskripsi_portofolio' => 'nullable',
            'foto_portofolio' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();

            // Upload file dulu
            if ($request->hasFile('foto_portofolio')) {
                $file = $request->file('foto_portofolio');
                $path = "foto_portofolio";
                $uploadedFilename = "foto-portofolio-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_portofolio'] = $uploadedFilename;
            }


            Portofolio::create($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.portofolio.index',
                [],
                'success',
                "Berhasil membuat portofolio baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_portofolio/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_portofolio/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Portofolio $portofolio)
    {
        return view('admin.portofolio.form', [
            'type' => 'update',
            'portofolio' => $portofolio
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portofolio $portofolio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portofolio $portofolio)
    {
        $validated = $request->validate([
            'judul_portofolio' => 'required',
            'deskripsi_portofolio' => 'nullable',
            'foto_portofolio' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();
            if (isset($validated['foto_portofolio']) && is_null($validated['foto_portofolio'])) {
                if (Storage::disk('public')
                    ->exists("foto_portofolio/{$portofolio->foto_portofolio}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_portofolio/{$portofolio->foto_portofolio}");
                }
            }

            // Upload file dulu
            if ($request->hasFile('foto_portofolio')) {
                if (
                    $portofolio->foto_portofolio
                    && Storage::disk('public')->exists("foto_portofolio/{$portofolio->foto_portofolio}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_portofolio/{$portofolio->foto_portofolio}");
                }
                $file = $request->file('foto_portofolio');
                $path = "foto_portofolio";
                $uploadedFilename = "foto-portofolio-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['foto_portofolio'] = $uploadedFilename;
            }


            $portofolio->update($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.portofolio.index',
                [],
                'success',
                "Berhasil mengupdate portofolio baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_portofolio/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_portofolio/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portofolio $portofolio)
    {
        if (
            $portofolio->foto_portofolio
            && Storage::disk('public')->exists("foto_portofolio/{$portofolio->foto_portofolio}")
        ) {
            Storage::disk('public')
                ->delete("foto_portofolio/{$portofolio->foto_portofolio}");
        }

        $portofolio->delete();
        return $this->backWithAlert('success', 'Berhasil menghapus data portofolio!');
    }
}
