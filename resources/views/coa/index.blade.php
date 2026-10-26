@extends('layouts.dashboard')

@section('title', 'Jenis COA')
@section('subtitle', 'Daftar Jenis Chart of Accounts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="data-table mb-4">
                <div class="table-header d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i> Data Jenis COA</h5>
                        <small class="text-muted">Daftar Jenis Chart of Accounts</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form class="d-flex" method="GET" action="{{ route('coa.index') }}">
                            <input name="q" value="{{ old('q', $q ?? '') }}" class="form-control form-control-sm"
                                type="search" placeholder="Cari kode atau nama..." aria-label="Search">
                            <button class="btn btn-sm btn-outline-secondary ms-2" type="submit">Search</button>
                            @if (!empty($q))
                                <a href="{{ route('coa.index') }}" class="btn btn-sm btn-outline-light ms-2">Bersihkan</a>
                            @endif
                        </form>
                        <a href="{{ route('coa.create') }}" class="btn btn-primary btn-sm">Tambah Jenis COA</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive desktop-only p-3">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th style="width:220px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coas as $coa)
                                <tr>
                                    <td>{{ $coa->code }}</td>
                                    <td>{{ $coa->name }}</td>
                                    <td>{{ Str::limit($coa->description, 60) }}</td>
                                    <td>
                                        <a href="{{ route('coa.show', $coa) }}"
                                            class="btn btn-sm btn-outline-secondary me-1">Lihat</a>
                                        <a href="{{ route('coa.edit', $coa) }}"
                                            class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                        <form action="{{ route('coa.destroy', $coa) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus Jenis COA?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada Jenis COA.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile list -->
                <div class="mobile-only p-3">
                    @forelse($coas as $coa)
                        <div class="mobile-contract-card mb-2">
                            <div class="meta">
                                <div class="title fw-bold">{{ $coa->code }} — {{ $coa->name }}</div>
                                <div class="subtitle text-muted">{{ Str::limit($coa->description, 80) }}</div>
                            </div>
                            <div class="actions mt-2">
                                <a href="{{ route('coa.edit', $coa) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">Belum ada Jenis COA.</div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="text-muted small">
                        @if ($coas->total() > 0)
                            Menampilkan {{ $coas->firstItem() }}—{{ $coas->lastItem() }} dari {{ $coas->total() }} hasil
                        @else
                            0 hasil
                        @endif
                    </div>
                    <div>
                        {{ $coas->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
