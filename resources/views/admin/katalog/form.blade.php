@extends('layouts.admin', ['title' => $type === 'add' ? 'Tambah' : 'Update'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header-title">
                        <h5 class="mb-0">{{ $type === 'add' ? 'Tambah Data katalog' : 'Update Data Katalog' }}</h5>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.katalog.index') }}" class="btn btn-sm btn-secondary float-end">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ $type === 'add' ? route('admin.katalog.store') : route('admin.katalog.update', $katalog->id) }}"
        class="card" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body row align-items-center">
            <div class="col-md-6 mb-3">
                <label for="nama_katalog" class="form-label">Nama Katalog</label>
                <input value="{{ $type === 'add' ? old('nama_katalog') : $katalog->nama_katalog }}" type="text"
                    name="nama_katalog" id="nama_katalog" class="form-control" placeholder="Masukan Nama Katalog" />
                @error('nama_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="harga_katalog" class="form-label">Harga Katalog</label>
                <input value="{{ $type === 'add' ? old('harga_katalog') : $katalog->harga_rupiah }}" type="text"
                    name="harga_katalog" inputmode="numeric" id="harga_katalog" class="form-control"
                    placeholder="Masukan Harga Katalog" />
                @error('harga_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="status_katalog" class="form-label">Status Katalog</label>
                <select name="status_katalog" id="status_katalog" class="form-control">
                    <option value="" hidden>Pilih Satu</option>
                    @foreach ($status_katalog as $item)
                        <option {{ $type === 'update' && $katalog->status_katalog === $item->value ? 'selected' : '' }}
                            value="{{ $item->value }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('status_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="diskon_katalog" class="form-label">Diskon Katalog</label>
                <input value="{{ $type === 'add' ? old('diskon_katalog') : $katalog->diskon_katalog }}" type="number"
                    name="diskon_katalog" placeholder="Masukan  Diskon Katalog (opsional)" id="diskon_katalog"
                    class="form-control">
                @error('diskon_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                @if ($type === 'update')
                    <div class="mb-2">
                        <img src="{{ $katalog->link_thumbnail_katalog }}" width="30%" alt="">
                    </div>
                @endif
                <label class="form-label" for="thumbnail_katalog">Foto Thumbnail Katalog</label>
                <input type="file" name="thumbnail_katalog" id="thumbnail_katalog"
                    placeholder="Masukan Thumbnail Katalog" class="form-control">
                @error('thumbnail_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="item_katalog" class="form-label">Item Katalog Statis</label>
                @if ($type === 'add')
                    <div id="input-wrapper">
                        <div class="input-group mb-2">
                            <input type="text" name="item_katalog[]" class="form-control" placeholder="Masukkan nilai">
                            <button class="btn btn-danger remove-field" type="button">
                                Hapus
                            </button>
                        </div>
                    </div>
                @else
                    <div id="input-wrapper">
                        @if (is_array($katalog->item_katalog))
                            @foreach ($katalog->item_katalog as $ik)
                                <div class="input-group mb-2">
                                    <input value="{{ $ik }}" type="text" name="item_katalog[]"
                                        class="form-control" placeholder="Masukkan nilai">
                                    <button class="btn btn-danger remove-field" type="button">
                                        Hapus
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
                <button id="add-field" class="btn btn-primary btn-sm mt-2" type="button">
                    Tambah Item
                </button>
                @error('item_katalog')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-12 mb-3 bg-body-secondary rounded p-2">
                <div id="dynamicForm"></div>

                <button id="addField" type="button" class="btn btn-success btn-sm">Tambah Item Dinamis</button>
            </div>
            <div class="col-md-12 mb-3">
                <label for="deskripsi_katalog" class="form-label">Deskripsi Katalog</label>
                <textarea name="deskripsi_katalog" placeholder="Masukan Deskripsi Katalog" id="deskripsi_katalog" cols="9"
                    rows="4" class="form-control">{{ $type === 'update' ? $katalog->deskripsi_katalog : '' }}</textarea>
                @error('deskripsi_katalog')
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

@push('js')
    @if ($type === 'update')
        <script>
            const savedDynamicData = @json($katalog->data_vendor);
            $("#dynamicForm").dynamicForm({
                fetchSources: [{
                        key: "gaun_pengantin",
                        name: "Gaun Pengantin",
                        url: "{{ route('admin.gaun-pengantin.getData') }}"
                    },
                    {
                        key: "gaun_pengantin_akad",
                        name: "Gaun Pengantin Akad",
                        url: "{{ route('admin.gaun-pengantin.getData.akad') }}"
                    },
                    {
                        key: "gaun_pengantin_resepsi",
                        name: "Gaun Pengantin Resepsi",
                        url: "{{ route('admin.gaun-pengantin.getData.resepsi') }}"
                    },
                    {
                        key: "gaun_pengantin_temu",
                        name: "Gaun Pengantin Temu",
                        url: "{{ route('admin.gaun-pengantin.getData.temu') }}"
                    },
                    {
                        key: "dekorasi",
                        name: "Dekorasi",
                        url: "{{ route('admin.dekorasi.getData') }}"
                    },
                    {
                        key: "tenda",
                        name: "Tenda",
                        url: "{{ route('admin.tenda.getData') }}"
                    },
                ],
            });

            // JIKA MODE UPDATE
            if (savedDynamicData) {
                Object.keys(savedDynamicData).forEach(key => {
                    $("#dynamicForm").data("plugin_dynamicForm").addRow({
                        key: key,
                        values: savedDynamicData[key]
                    });
                });
            }
        </script>
    @else
        <script>
            const savedDynamicData = null;
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
            $('#harga_katalog').on('input', function() {
                let val = $(this).val().replace(/[^0-9]/g, '')
                $(this).val(formatRupiah(val));
            })

            // Tambah field baru
            $("#add-field").on("click", function() {
                const field = `
            <div class="input-group mb-2">
                <input type="text" name="item_katalog[]" class="form-control" placeholder="Masukkan nilai">
                <button class="btn btn-danger remove-field" type="button">Hapus</button>
            </div>
        `;
                $("#input-wrapper").append(field);
            });

            // Hapus field (pakai event delegation)
            $(document).on("click", ".remove-field", function() {
                $(this).closest(".input-group").remove();
            });

            $(document).ready(function() {
                $("#dynamicForm").dynamicForm({
                    fetchSources: [{
                            key: "gaun_pengantin",
                            name: "Gaun Pengantin",
                            url: "{{ route('admin.gaun-pengantin.getData') }}"
                        },
                        {
                            key: "gaun_pengantin_akad",
                            name: "Gaun Pengantin Akad",
                            url: "{{ route('admin.gaun-pengantin.getData.akad') }}"
                        },
                        {
                            key: "gaun_pengantin_resepsi",
                            name: "Gaun Pengantin Resepsi",
                            url: "{{ route('admin.gaun-pengantin.getData.resepsi') }}"
                        },
                        {
                            key: "gaun_pengantin_temu",
                            name: "Gaun Pengantin Temu",
                            url: "{{ route('admin.gaun-pengantin.getData.temu') }}"
                        },
                        {
                            key: "dekorasi",
                            name: "Dekorasi",
                            url: "{{ route('admin.dekorasi.getData') }}"
                        },
                        {
                            key: "tenda",
                            name: "Tenda",
                            url: "{{ route('admin.tenda.getData') }}"
                        },
                    ]
                });
            });
        });
    </script>
@endpush
