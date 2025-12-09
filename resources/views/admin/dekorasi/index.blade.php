@extends('layouts.admin', ['title' => 'Dekorasi'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data Dekorasi</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.dekorasi.create') }}" class="btn btn-sm btn-primary float-end">Tambah
                        Dekkorasi</a>
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
                            <th>Nama Dekorasi</th>
                            <th>Foto Dekorasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dekorasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_dekorasi }}</td>
                                <td width="30%" class="text-center">
                                    <img class="rounded" width="30%" src="{{ $item->link_foto_dekorasi }}"
                                        alt="{{ $item->foto_dekorasi }}">
                                </td>
                                <td width="20%">
                                    <a href="{{ route('admin.dekorasi.show', $item->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        href="{{ route('admin.dekorasi.destroy', $item->id) }}"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">{{ $dekorasi->links() }}</div>
    </div>
@endsection
