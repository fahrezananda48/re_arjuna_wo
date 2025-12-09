@extends('layouts.admin', ['title' => 'Laporan'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Laporan</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- <a href="{{ route('admin.customer.create') }}" class="btn btn-sm btn-primary float-end">Tambah
                        Customer</a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Customer</th>
                            <th>Nomor Telpon Customer</th>
                            <th>Alamat Customer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer"></div>
    </div>
@endsection
