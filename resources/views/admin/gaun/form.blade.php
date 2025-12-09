@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah' : 'Update'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">
                            {{ $type === 'add' ? 'Tambah Data Gaun Pengantin' : 'Update Data Gaun Pengantin' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.gaun-pengantin.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form
        action="{{ $type === 'add' ? route('admin.gaun-pengantin.store') : route('admin.gaun-pengantin.update', $gaun->id) }}"
        class="card" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body row align-items-center">
            <div class="col-md-12 mb-3">
                <label for="nama_gaun" class="form-label">Nama gaun</label>
                <input value="{{ $type === 'add' ? old('nama_gaun') : $gaun->nama_gaun }}" type="text" name="nama_gaun"
                    id="nama_gaun" class="form-control" placeholder="Masukan Nama gaun" />
                @error('nama_gaun')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                @if ($type === 'update')
                    <div class="mb-2">
                        <img src="{{ $gaun->link_foto_gaun }}" width="30%" alt="">
                    </div>
                @endif
                <label class="form-label" for="foto_gaun">Foto Gaun Pengantin</label>
                <input type="file" name="foto_gaun" id="foto_gaun" placeholder="Masukan Foto Gaun Pengantin"
                    class="form-control">
                @error('foto_gaun')
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
