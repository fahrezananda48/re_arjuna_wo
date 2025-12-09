@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah' : 'Update'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">
                            {{ $type === 'add' ? 'Tambah Data Dekorasi' : 'Update Data Dekorasi' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.dekorasi.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ $type === 'add' ? route('admin.dekorasi.store') : route('admin.dekorasi.update', $dekorasi->id) }}"
        class="card" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body row align-items-center">
            <div class="col-md-12 mb-3">
                <label for="nama_dekorasi" class="form-label">Nama dekorasi</label>
                <input value="{{ $type === 'add' ? old('nama_dekorasi') : $dekorasi->nama_dekorasi }}" type="text"
                    name="nama_dekorasi" id="nama_dekorasi" class="form-control" placeholder="Masukan Nama dekorasi" />
                @error('nama_dekorasi')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                @if ($type === 'update')
                    <div class="mb-2">
                        <img src="{{ $dekorasi->link_foto_dekorasi }}" width="30%" alt="">
                    </div>
                @endif
                <label class="form-label" for="foto_dekorasi">Foto Dekorasi</label>
                <input type="file" name="foto_dekorasi" id="foto_dekorasi" placeholder="Masukan Foto Dekorasi"
                    class="form-control">
                @error('foto_dekorasi')
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
