@extends('admin.layout')
@section('title', 'Team List - Administrator')
@section('content')

<style>
    .table-scroll-container {
        width: 100%;
        max-height: 400px; /* Atur tinggi maksimum untuk kontainer tabel */
        overflow-x: auto; /* Hanya scroll horizontal */
        overflow-y: auto; /* Scroll vertikal jika diperlukan */
        border: 1px solid #dee2e6; /* Tambahkan border untuk kontainer */
        border-radius: 0.25rem; /* Tambahkan border radius untuk sudut */
    }

    .scroll-table {
        min-width: 100%; /* Mengisi lebar kontainer */
        table-layout: auto; /* Menyesuaikan lebar kolom berdasarkan konten */
    }

    .table th,
    .table td {
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        padding: 0.5rem; /* Padding untuk tampilan yang lebih minimalis */
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        position: sticky; /* Membuat header tetap */
        top: 0; /* Jarak dari atas */
        z-index: 10; /* Pastikan header berada di atas konten lainnya */
    }

    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .text-muted {
        color: #6c757d;
    }

    .container {
        padding-left: 0;
        padding-right: 0;
    }
</style>

<div class="container">
    <h2 class="mb-4">Daftar Tim Terdaftar</h2>

    <div class="table-scroll-container border rounded shadow-sm">
        <table class="table table-bordered table-hover scroll-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>School Name</th>
                    <th>Referral Code</th>
                    <th>Season</th>
                    <th>Series</th>
                    <th>Competition</th>
                    <th>Team Category</th>
                    <th>Registered By</th>
                    <th>Locked Status</th>
                    <th>Verification Status</th>
                    <th>Recommendation Letter</th>
                    <th>Payment Proof</th>
                    <th>Payment Status</th>
                    <th>Dibuat</th>
                    <th>Diperbarui</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teamList as $team)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $team->school_name }}</td>
                        <td>{{ $team->referral_code }}</td>
                        <td>{{ $team->season }}</td>
                        <td>{{ $team->series }}</td>
                        <td>{{ $team->competition }}</td>
                        <td>{{ $team->team_category }}</td>
                        <td>{{ $team->registered_by }}</td>
                        <td>{{ $team->locked_status }}</td>
                        <td>{{ $team->verification_status }}</td>
                        <td>
                            @if ($team->recommendation_letter)
                                <a href="{{ asset('storage/' . $team->recommendation_letter) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($team->payment_proof)
                                <a href="{{ asset('storage/' . $team->payment_proof) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $team->payment_status ?? '-' }}</td>
                        <td>{{ $team->created_at->format('d M Y') }}</td>
                        <td>{{ $team->updated_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.team-list.show', $team->team_id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="text-center text-muted">Belum ada tim terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection