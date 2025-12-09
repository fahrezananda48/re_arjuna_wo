@extends('layouts.login', ['title' => 'Login'])
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
                        <form method="post" action="{{ route('login') }}" class="card-body">
                            @csrf
                            <div class="text-center mb-3">
                                <a class="h3 " href="#">Login {{ config('app.name') }}</a>
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Masuan email anda" />
                                @error('email')
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
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="shopass" />
                                    <label class="form-check-label text-muted" for="shopass">Tampilkan
                                        Password</label>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary shadow px-sm-4">Login</button>
                            </div>
                            <div class="mt-3 text-center">
                                <p>Belum punya akun? <a href="{{ route('daftar') }}">Daftar</a></p>
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
