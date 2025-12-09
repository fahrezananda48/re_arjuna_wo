@extends('layouts.admin', ['title' => 'Customer'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data customer</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-sm btn-primary float-end">Tambah
                        Customer</a>
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
                        @foreach ($customer as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_customer }}</td>
                                <td>{{ $item->nomor_telp_customer }}</td>
                                <td>{{ $item->alamat_lengkap_customer }}</td>
                                <td width="20%">
                                    <a href="{{ route('admin.customer.show', $item->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <a onclick="return confirm('Apkah anda yakin ingin menghapus data ini?')"
                                        href="{{ route('admin.customer.destroy', $item->id) }}"
                                        class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">{{ $customer->links() }}</div>
    </div>
@endsection
