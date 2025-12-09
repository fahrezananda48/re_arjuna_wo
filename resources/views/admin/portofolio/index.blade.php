@extends('layouts.admin', ['title' => 'Portofolio'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data Portofolio</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.portofolio.create') }}" class="btn btn-sm btn-primary float-end">Tambah
                        Portofolio</a>
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
                            <th>Judul Portofolio</th>
                            <th>Thumbnail Portofolio</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($portofolio as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->judul_portofolio }}</td>
                                <td width="30%" class="text-center">
                                    <img class="rounded" width="30%" src="{{ $item->link_foto_portofolio }}"
                                        alt="{{ $item->foto_portofolio }}">
                                </td>
                                <td width="20%">
                                    <a href="{{ route('admin.portofolio.show', $item->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        href="{{ route('admin.portofolio.destroy', $item->id) }}"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">{{ $portofolio->links() }}</div>
    </div>
@endsection
