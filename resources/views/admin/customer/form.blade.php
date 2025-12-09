@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah Customer' : 'Update Customer'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">{{ $type === 'add' ? 'Tambah Data Customer' : 'Update Data Customer' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.customer.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ $type === 'add' ? route('admin.customer.store') : route('admin.customer.update', $customer->id) }}"
        class="card" method="post">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <label for="nama_customer" class="form-label">Nama Customer</label>
                <input type="text" name="nama_customer" id="nama_customer" class="form-control"
                    placeholder="Masukan Nama Customer"
                    value="{{ $type === 'add' ? old('nama_customer') : $customer->nama_customer }}" />
                @error('nama_customer')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nomor_telp_customer" class="form-label">Nomor Telpon Customer</label>
                <input type="text" name="nomor_telp_customer" id="nomor_telp_customer" class="form-control"
                    placeholder="Masukan Nomor Telpon Customer"
                    value="{{ $type === 'add' ? old('nomor_telp_customer') : $customer->nomor_telp_customer }}" />
                @error('nomor_telp_customer')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="alamat_lengkap_customer" class="form-label">Alamat Lengkap Customer</label>
                <textarea name="alamat_lengkap_customer" id="alamat_lengkap_customer" class="form-control"
                    placeholder="Masukan Nama Customer">{{ $type === 'add' ? old('alamat_lengkap_customer') : $customer->alamat_lengkap_customer }}</textarea>
                @error('alamat_lengkap_customer')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit"
                class="btn float-end btn-sm btn-{{ $type === 'add' ? 'success' : 'warning' }}">{{ $type === 'add' ? 'Tambah' : 'Update' }}</button>
        </div>
    </form>
@endsection
