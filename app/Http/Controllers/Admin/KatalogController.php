<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusKatalogEnum;
use App\Http\Controllers\Controller;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KatalogController extends Controller
{
    public function index()
    {

        return view('admin.katalog.index', [
            'katalog' => Katalog::paginate(25)
        ]);
    }
    public function create()
    {
        return view('admin.katalog.form', [
            'type' => 'add',
            'status_katalog' => StatusKatalogEnum::cases()
        ]);
    }
    public function show(Katalog $katalog)
    {
        return view('admin.katalog.form', [
            'type' => 'update',
            'katalog' => $katalog,
            'status_katalog' => StatusKatalogEnum::cases()
        ]);
    }



    public function store(Request $request)
    {

        $validated = $request->validate([
            "nama_katalog" => "required",
            "harga_katalog" => "required",
            "status_katalog" => "required|in:aktif,diskon,belum_aktif",
            "diskon_katalog" => "nullable",
            "item_katalog" => "nullable",
            "item_katalog.*" => "nullable",
            "deskripsi_katalog" => "nullable",
            "field_value" => "nullable",
            "field_value.*" => "nullable",
            "thumbnail_katalog" => "required|image|max:2048|mimes:png,jpg,jpeg,webp"
        ]);

        $uploadedFilename = null;

        try {
            DB::beginTransaction();

            // Upload file dulu
            if ($request->hasFile('thumbnail_katalog')) {
                $file = $request->file('thumbnail_katalog');
                $path = "foto_katalog";
                $uploadedFilename = "foto-katalog-" . Str::uuid() . "." . $file->getClientOriginalExtension();

                // Simpan
                $file->storeAs($path, $uploadedFilename, 'public');

                // Set ke validated
                $validated['thumbnail_katalog'] = $uploadedFilename;
            }

            if (is_array($request->item_katalog)) {
                $validated['item_katalog'] = $request->item_katalog;
            }

            $validated['harga_katalog'] = $this->clearAngka($validated['harga_katalog']);
            $validated['data_vendor'] = $validated['field_value'];
            Katalog::create($validated);

            DB::commit();

            return $this->toWithAlert(
                'admin.katalog.index',
                [],
                'success',
                "Berhasil membuat katalog baru!"
            );
        } catch (\Exception $th) {

            DB::rollBack();

            // Hapus file kalau terlanjur diupload
            if ($uploadedFilename && Storage::disk('public')->exists("foto_katalog/$uploadedFilename")) {
                Storage::disk('public')->delete("foto_katalog/$uploadedFilename");
            }

            return $this->backWithAlert('danger', $th->getMessage());
        }
    }


    public function update(Katalog $katalog, Request $request)
    {
        $validated = $request->validate([
            "nama_katalog" => "required",
            "harga_katalog" => "required",
            "status_katalog" => "required|in:aktif,diskon,belum_aktif",
            "diskon_katalog" => "nullable",
            "field_value" => "nullable",
            "field_value.*" => "nullable",
            "deskripsi_katalog" => "nullable",
            "thumbnail_katalog" => "nullable|image|max:2048|mimes:png,jpg,jpeg,webp"
        ]);
        try {
            if (isset($validated['thumbnail_katalog']) && is_null($validated['thumbnail_katalog'])) {
                if (Storage::disk('public')
                    ->exists("foto_katalog/{$katalog->thumbnail_katalog}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_katalog/{$katalog->thumbnail_katalog}");
                }
            }
            if ($request->hasFile('thumbnail_katalog')) {
                if (
                    $katalog->thumbnail_katalog
                    && Storage::disk('public')->exists("foto_katalog/{$katalog->thumbnail_katalog}")
                ) {
                    Storage::disk('public')
                        ->delete("foto_katalog/{$katalog->thumbnail_katalog}");
                }
                $file = $request->file('thumbnail_katalog');
                $path = "foto_katalog";
                $filename = "foto-katalog-" . Str::uuid() . "." . $file->getClientOriginalExtension();
                $file->storeAs($path, $filename, 'public');
                $validated['thumbnail_katalog'] = $filename;
            }
            if (is_array($request->item_katalog)) {
                $validated['item_katalog'] = $request->item_katalog;
            }
            $validated['harga_katalog'] = $this->clearAngka($validated['harga_katalog']);
            $validated['data_vendor'] = $validated['field_value'];

            $katalog->update($validated);
            return $this->toWithAlert(
                'admin.katalog.index',
                [],
                'success',
                "Berhasil mengupdate katalog baru!"
            );
        } catch (\Exception $th) {
            return $this->backWithAlert('danger', $th->getMessage());
        }
    }

    public function destroy(Katalog $katalog)
    {
        if (Storage::disk('public')
            ->exists("foto_katalog/{$katalog->thumbnail_katalog}")
        ) {
            Storage::disk('public')
                ->delete("foto_katalog/{$katalog->thumbnail_katalog}");
        }
        $katalog->delete();
        return $this->backWithAlert('success', "Berhasil menghapus katalog");
    }
}
