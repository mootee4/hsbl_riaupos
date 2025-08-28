@extends('admin.layout')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'city'; @endphp
@include('partials.tabs', compact('activeTab'))

@push('scripts')
@include('partials.sweetalert')
@endpush

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
@endpush

<div class="container">
    <h2 class="mb-4">Data Kota</h2>

    {{-- Form Tambah Kota --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold">Tambah Kota</div>
        <div class="card-body">
            <form action="{{ route('admin.city.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="city_name" class="form-label">Nama Kota</label>
                    <input type="text" name="city_name" id="city_name" class="form-control" placeholder="Contoh: Pekanbaru">
                </div>
                <div class="flex justify-center pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Add City
                </button>
            </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data Kota --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Daftar Kota</div>
        <div class="card-body p-0">
            <table class="table table-hover table-bordered m-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width: 5%;">No.</th>
                        <th>Nama Kota</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cities as $index => $city)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $city->city_name }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.data.delete') }}" onsubmit="return confirm('Yakin ingin menghapus kota ini?')">
                                @csrf
                                <input type="hidden" name="table" value="cities">
                                <input type="hidden" name="field" value="id">
                                <input type="hidden" name="value" value="{{ $city->id }}">
                                <button type="submit" class="btn btn-sm btn-danger btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">Belum ada data kota.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
