@extends('layouts.admin', ['title' => 'Kategori'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">Data kategori katalog</h5>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <button data-bs-toggle="modal" data-bs-target="#modalTambah" type="button"
                        class="btn btn-primary btn-sm float-end">Tambah kategori</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card ">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kategori }}</td>
                                <td width="20%">
                                    <button data-nama="{{ $item->nama_kategori }}" data-id="{{ $item->id }}"
                                        type="button" class="btn btn-warning btn-sm" onclick="edit(this)">Edit</button>
                                    <a href="{{ route('admin.kategori.destroy', $item->id) }}"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        type="button" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $kategori->links() }}
        </div>
    </div>
@endsection


@push('utils')
    <!-- Modal Tambah-->
    <div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <form id="formTambah" method="post" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Tambah Data Kategori Katalog
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control"
                            placeholder="Masukan Nama Kategori Katalog" aria-describedby="helpId" />
                        <small id="helpId" class="text-danger"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update-->
    <div class="modal fade" id="modalEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <form id="formEdit" method="post" class="modal-content">
                @csrf
                <input type="hidden" id="idEdit">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Tambah Data Kategori Katalog
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori_edit" class="form-control"
                            placeholder="Masukan Nama Kategori Katalog" aria-describedby="helpId" />
                        <small id="helpId" class="text-danger"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('js')
    <script>
        $('#formTambah').submit((e) => {
            e.preventDefault();

            $.ajax({
                url: '{{ route('admin.kategori.store') }}',
                method: "post",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nama_kategori: $('#nama_kategori').val()
                },
                success: (res) => {
                    $('#modalTambah').modal('hide');
                    window.location.reload();
                },
                error: (err) => {
                    if (err.status === 422) {
                        const errors = err.responseJSON.errors;

                        $('small#helpId').text("")
                        $.each(errors, (key, item) => {
                            $('small#helpId').text(item[0])

                        })
                    } else {
                        $('#modalTambah').modal('hide')
                    }
                }
            })
        })

        function edit(el) {
            let nama_kategori = $(el).data('nama')
            let id = $(el).data('id')

            $('#idEdit').val(id);
            $('#nama_kategori_edit').val(nama_kategori);

            $('#modalEdit').modal('show');
        }

        $('#formEdit').submit((e) => {
            e.preventDefault();
            let id = $('#idEdit').val();
            $.ajax({
                url: '{{ route('admin.kategori.update', ':id') }}'.replace(':id', id),
                method: "post",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nama_kategori: $('#nama_kategori_edit').val()
                },
                success: (res) => {
                    $('#modalEdit').modal('hide');
                    window.location.reload();
                },
                error: (err) => {
                    if (err.status === 422) {
                        const errors = err.responseJSON.errors;

                        $('small#helpId').text("")
                        $.each(errors, (key, item) => {
                            $('small#helpId').text(item[0])

                        })
                    } else {
                        $('#modalEdit').modal('hide')
                    }
                }
            })
        })
    </script>
@endpush
