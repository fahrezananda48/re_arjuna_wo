@extends('layouts.admin', ['title' => 'Tenda'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data Tenda</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.tenda.create') }}" class="btn btn-sm btn-primary float-end">Tambah
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
                            <th>Nama Tenda</th>
                            <th>Foto Tenda</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenda as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_tenda }}</td>
                                <td width="30%" class="text-center">
                                    <img class="rounded" width="30%" src="{{ $item->link_foto_tenda }}"
                                        alt="{{ $item->foto_tenda }}">
                                </td>
                                <td width="20%">
                                    <a href="{{ route('admin.tenda.show', $item->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        href="{{ route('admin.tenda.destroy', $item->id) }}"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">{{ $tenda->links() }}</div>
    </div>
@endsection
