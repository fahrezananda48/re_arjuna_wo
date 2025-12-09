@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah' : 'Update'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">
                            {{ $type === 'add' ? 'Tambah Data Tenda' : 'Update Data Tenda' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.tenda.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ $type === 'add' ? route('admin.tenda.store') : route('admin.tenda.update', $tenda->id) }}"
        class="card" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body row align-items-center">
            <div class="col-md-12 mb-3">
                <label for="nama_tenda" class="form-label">Nama tenda</label>
                <input value="{{ $type === 'add' ? old('nama_tenda') : $tenda->nama_tenda }}" type="text"
                    name="nama_tenda" id="nama_tenda" class="form-control" placeholder="Masukan Nama tenda" />
                @error('nama_tenda')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                @if ($type === 'update')
                    <div class="mb-2">
                        <img src="{{ $tenda->link_foto_tenda }}" width="30%" alt="">
                    </div>
                @endif
                <label class="form-label" for="foto_tenda">Foto Dekorasi</label>
                <input type="file" name="foto_tenda" id="foto_tenda" placeholder="Masukan Foto Dekorasi"
                    class="form-control">
                @error('foto_tenda')
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
