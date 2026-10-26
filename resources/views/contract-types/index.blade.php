@extends('layouts.dashboard')

@section('title', 'Jenis Kontrak')
@section('subtitle', 'Kategori kontrak yang tersedia')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><i class="fas fa-layer-group text-primary me-2"></i>Jenis Kontrak</h5>
                <small class="text-muted">Jenis yang dapat dipilih pada setiap kontrak.</small>
            </div>
            <span class="badge bg-primary">{{ count($contractTypes) }} jenis</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px">No.</th>
                        <th>Jenis Kontrak</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contractTypes as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-primary-subtle text-primary border">{{ $type['name'] }}</span></td>
                            <td>{{ $type['description'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection