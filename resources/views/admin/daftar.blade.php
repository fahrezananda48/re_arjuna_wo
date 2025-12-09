@extends('layouts.login', ['title' => 'Daftar'])
@section('content')
    <div class="auth-main">

        <div class="auth-wrapper v1">

            <div class="auth-form">
                <div class="position-relative my-5">
                    <div class="card mb-0">
                        @session('alert')
                            <div class="mx-3 mt-3">
                                <div class="alert alert-{{ session()->get('alert')['type'] }} alert-dismissible fade show"
                                    role="alert">
                                    {{ session()->get('alert')['message'] }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                            @push('js')
                                <script>
                                    setTimeout(() => {
                                        $('.alert').remove();
                                    }, 2000)
                                </script>
                            @endpush
                        @endsession
                        <form method="post" action="{{ route('daftar.post') }}" class="card-body">
                            @csrf
                            <div class="text-center mb-3">
                                <a class="h3" href="#">Daftar {{ config('app.name') }}</a>
                            </div>
                            <div class="form-group mb-3">
                                <input type="nama_lengkap" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                    value="{{ old('nama_lengkap') }}" placeholder="Masukan nama lengkap anda" />
                                @error('nama_lengkap')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Masukan email anda" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input type="telp" class="form-control" id="nomor_telp" name="nomor_telp"
                                    value="{{ old('nomor_telp') }}" placeholder="Masukan nomor telp anda" />
                                @error('nomor_telp')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukan password anda" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" placeholder="Masukan alamat_lengkap anda"></textarea>
                                @error('alamat_lengkap')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="shopass" />
                                    <label class="form-check-label text-muted" for="shopass">Tampilkan
                                        Password</label>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary shadow px-sm-4">Daftar</button>
                            </div>
                            <div class="mt-3 text-center">
                                <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#shopass').change((e) => {
            const passType = $('#password');
            const isChecked = $('#shopass').is(':checked');

            isChecked && passType.attr('type') === "password" ? passType.attr('type', 'text') : passType.attr(
                'type', 'password')


        });
        @if (Auth::check())
            $(document).ready(function() {
                let redirectUrl = localStorage.getItem("redirect_after_login");

                if (redirectUrl) {
                    localStorage.removeItem("redirect_after_login");
                    window.location.href = redirectUrl;
                }
            })
        @endif
    </script>
@endpush
