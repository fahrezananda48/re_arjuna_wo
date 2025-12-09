@extends('layouts.admin', ['title' => 'Katalog'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data katalog</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.katalog.create') }}" class="btn btn-sm btn-primary float-end">Tambah katalog</a>
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
                            <th>Nama Katalog</th>
                            <th>Harga Katalog</th>
                            <th>Thumbnail Katalog</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($katalog as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_katalog }}</td>
                                <td>{{ $item->harga_rupiah }}</td>
                                <td width="30%" class="text-center">
                                    <img class="rounded" width="30%" src="{{ $item->link_thumbnail_katalog }}"
                                        alt="{{ $item->thumbnail_katalog }}">
                                </td>
                                <td width="20%">
                                    <a href="{{ route('admin.katalog.show', $item->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        href="{{ route('admin.katalog.destroy', $item->id) }}"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">{{ $katalog->links() }}</div>
    </div>
@endsection
