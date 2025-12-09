@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah' : 'Update'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">{{ $type === 'add' ? 'Tambah Data Portofolio' : 'Update Data Katalog' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.portofolio.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form
        action="{{ $type === 'add' ? route('admin.portofolio.store') : route('admin.portofolio.update', $portofolio->id) }}"
        class="card" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body row align-items-center">
            <div class="col-md-6 mb-3">
                <label for="judul_portofolio" class="form-label">Judul Portofolio</label>
                <input value="{{ $type === 'add' ? old('judul_portofolio') : $portofolio->judul_portofolio }}"
                    type="text" name="judul_portofolio" id="judul_portofolio" class="form-control"
                    placeholder="Masukan Judul Portofolio" />
                @error('judul_portofolio')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                @if ($type === 'update')
                    <div class="mb-2">
                        <img src="{{ $portofolio->link_foto_portofolio }}" width="30%" alt="">
                    </div>
                @endif
                <label class="form-label" for="foto_portofolio">Foto Portofolio</label>
                <input type="file" name="foto_portofolio" id="foto_portofolio" placeholder="Masukan Thumbnail Katalog"
                    class="form-control">
                @error('foto_portofolio')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="deskripsi_portofolio" class="form-label">Deskripsi Portofolio</label>
                <textarea name="deskripsi_portofolio" placeholder="Masukan Deskripsi Portofolio" id="deskripsi_portofolio"
                    cols="9" rows="4" class="form-control">{{ $type === 'update' ? $portofolio->deskripsi_portofolio : '' }}</textarea>
                @error('deskripsi_portofolio')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="card-footer">
                <button type="submit"
                    class="btn btn-sm float-end btn-{{ $type === 'add' ? 'success' : 'warning' }}">{{ $type === 'add' ? 'Tambah' : 'Update' }}</button>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            // Tambah field baru
            $("#add-field").on("click", function() {
                const field = `
            <div class="input-group mb-2">
                <input type="text" name="item_portofolio[]" class="form-control" placeholder="Masukkan nilai">
                <button class="btn btn-danger remove-field" type="button">Hapus</button>
            </div>
        `;
                $("#input-wrapper").append(field);
            });

            // Hapus field (pakai event delegation)
            $(document).on("click", ".remove-field", function() {
                $(this).closest(".input-group").remove();
            });

        });
    </script>
@endpush
