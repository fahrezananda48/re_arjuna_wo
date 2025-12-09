@extends('layouts.admin', ['title' => 'Beranda'])
@section('page-header')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="mb-0">Sample Page</h5>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Other</a></li>
                        <li class="breadcrumb-item" aria-current="page">Sample Page</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-body">Beranda admin</div>
@endsection
