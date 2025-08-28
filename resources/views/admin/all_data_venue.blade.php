@extends('admin.layout')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'venue'; @endphp
@include('partials.tabs', compact('activeTab'))
@include('partials.sweetalert')

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function openEditVenueModal(button) {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const cityId = button.dataset.city;
        const location = button.dataset.location;

        document.getElementById('edit-venue-id').value = id;
        document.getElementById('edit-venue-name').value = name;
        document.getElementById('edit-venue-location').value = location;
        document.getElementById('edit-venue-city').value = cityId;

        document.getElementById('editVenueModal').classList.remove('hidden');
    }


    function closeEditVenueModal() {
        document.getElementById('editVenueModal').classList.add('hidden');
    }


    function showImage(src) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImage').src = src;
        modal.show();
    }
</script>
@endpush

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
<style>
    .pagination .active span {
        background-color: #2563eb;
        color: white;
        border-radius: 9999px;
        padding: 0.5rem 1rem;
    }

    .zoom-container img {
        transition: transform 0.25s ease;
        cursor: zoom-in;
    }

    .zoom-container:hover img {
        transform: scale(2);
        z-index: 100;
    }
</style>
@endpush

<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">Venue Data</h2>

    {{-- Form Tambah Venue --}}
    <div class="bg-white p-6 rounded shadow mb-2">
        <form action="{{ route('admin.venue.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Venue Name</label>
                    <input type="text" name="venue_name" class="form-control w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">City</label>
                    <select name="city_id" class="form-control w-full">
                        <option value="" disabled selected>Select City</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="location" class="form-control w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Layout</label>
                    <input type="file" name="layout" class="form-control w-full" accept="image/*">
                </div>
            </div>
            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </form>
    </div>

    {{-- List Venue --}}
    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('admin.venue.store') }}" method="GET" class="mb-4">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <label>Show</label>
                <select name="per_page" class="form-control w-auto" onchange="this.form.submit()">
                    <option value="10" {{ request()->get('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->get('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->get('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <label>entries</label>
                <span class="mx-2">|</span>
                <select name="city_id" class="form-control w-auto" onchange="this.form.submit()">
                    <option value="">-- Filter by City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request()->get('city_id') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-center">No</th>
                        <th class="px-4 py-2 border text-center">Venue Name</th>
                        <th class="px-4 py-2 border text-center">City</th>
                        <th class="px-4 py-2 border text-center">Location</th>
                        <th class="px-4 py-2 border text-center">Layout</th>
                        <th class="px-4 py-2 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($venues as $venue)
                    <tr>
                        <td class="px-4 py-2 border text-center">{{ ($venues->currentPage() - 1) * $venues->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $venue->venue_name }}</td>
                        <td class="px-4 py-2 border">{{ $venue->city->city_name ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $venue->location ?? '-' }}</td>
                        <td class="p-2 border text-center">
                            @if ($venue->layout)
                            <img src="{{ asset('storage/' . $venue->layout) }}" width="100" onclick="showImage(this.src)">
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-700 text-xs"
                                onclick="openEditVenueModal(this)"
                                data-id="{{ $venue->id }}"
                                data-name="{{ $venue->venue_name }}"
                                data-city="{{ $venue->city_id }}"
                                data-location="{{ $venue->location }}">
                                ✏️ Edit
                            </button>

                            <form action="{{ route('admin.data.delete') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="table" value="venue">
                                <input type="hidden" name="id" value="{{ $venue->id }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs btn-delete">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 border text-center">No venues available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="modal fade" id="imageModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body p-0 text-center">
                            <img id="modalImage" src="" alt="Expanded layout" class="img-fluid rounded">
                        </div>
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-center items-center text-center">
                {{ $venues->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Venue --}}
<div id="editVenueModal" class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md relative">
        <h3 class="text-xl font-semibold mb-4">Edit Venue</h3>
        <form method="POST" action="{{ route('admin.data.edit') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="table" value="venue">
            <input type="hidden" name="id" id="edit-venue-id">

            <div class="mb-4">
                <label for="edit-venue-name" class="block text-sm font-medium mb-1">Venue Name</label>
                <input type="text" name="venue_name" id="edit-venue-name" class="form-control w-full" required>
            </div>

            <div class="mb-4">
                <label for="edit-venue-city" class="block text-sm font-medium mb-1">City</label>
                <select name="city_id" id="edit-venue-city" class="form-control w-full" required>
                    <option value="" disabled selected>-- Select City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="edit-venue-location" class="block text-sm font-medium mb-1">Location</label>
                <input type="text" name="location" id="edit-venue-location" class="form-control w-full" required>
            </div>

            <div class="mb-4">
                <label for="edit-venue-layout" class="block text-sm font-medium mb-1">New Layout (optional)</label>
                <input type="file" name="layout" id="edit-venue-layout" class="form-control w-full" accept="image/*">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditVenueModal()" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection