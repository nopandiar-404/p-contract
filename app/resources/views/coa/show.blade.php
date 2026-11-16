@extends('layouts.dashboard')

@section('title', 'Detail Jenis COA')
@section('subtitle', 'Informasi lengkap Jenis COA')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $coa->name }} <small class="text-muted">({{ $coa->code }})</small></h5>
                    <p class="card-text">{{ $coa->description ?? '-' }}</p>

                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <a href="{{ route('coa.edit', $coa) }}" class="btn btn-outline-primary">Edit</a>
                        <a href="{{ route('coa.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
