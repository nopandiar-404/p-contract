@extends('layouts.dashboard')

@section('title', 'Tambah Jenis COA')
@section('subtitle', 'Buat Jenis Chart of Accounts baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Tambah Jenis COA</h5>
                        <small class="text-muted">Isi detail Jenis COA. Kode harus unik.</small>
                    </div>
                    <div>
                        <a href="{{ route('coa.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('coa.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="code" class="form-label">Kode <span class="text-muted small">(maks.
                                        50)</span></label>
                                <input type="text" name="code" id="code"
                                    class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}"
                                    maxlength="50" required autofocus>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-text">Contoh: A001 atau EXP-01</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-8">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    maxlength="255" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi <span
                                        class="text-muted small">(opsional)</span></label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('coa.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple client-side feedback: focus first invalid input after server validation
        (function() {
            const invalid = document.querySelector('.is-invalid');
            if (invalid) invalid.focus();
        })();
    </script>
@endsection
