@extends('admin.layout')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'school'; @endphp
@include('partials.tabs', compact('activeTab'))

@push('scripts')
@include('partials.sweetalert')
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
</style>
@endpush

<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">School Data</h2>
    {{-- Add School Form --}}
    <div class="bg-white p-6 rounded shadow mb-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-md">Add School</h3>
        </div>
        <form method="POST" action="{{ route('admin.school.store') }}">
            @csrf
            <!-- Row 1: School Name + City -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- School Name -->
                <input
                    type="text"
                    name="school_name"
                    placeholder="School Name"
                    class="border px-3 py-2 rounded w-full"/>
                <!-- City -->
                <select
                    name="city_id"
                    class="border px-3 py-2 rounded w-full">
                    <option value="">-- Select City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Row 2: Category + Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Category -->
                <select name="category_name" class="border px-3 py-2 rounded w-full">
                    <option value="">-- Select Category --</option>
                    @foreach(['SMA', 'SMK', 'MA'] as $cat)
                    <option value="{{ $cat }}" {{ old('category_name') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                    @endforeach
                </select>
                <!-- Type -->
                <select name="type" class="border px-3 py-2 rounded w-full">
                    <option value="">-- Select Type --</option>
                    @foreach(['NEGERI', 'SWASTA'] as $type)
                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-center">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Add School
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow mb-2">
        <h3 class="text-lg font-semibold md:mb-0">Filter</h3>
        <form method="GET" action="{{ route('admin.data.store') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 mt-2">
                {{-- Filter by City --}}
                <select name="city_filter"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Filter by City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_filter') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                    @endforeach
                </select>

                {{-- Filter by Category --}}
                <select name="category_filter"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Filter by Category --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category_filter') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>

                {{-- Filter by Type --}}
                <select name="type_filter"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Filter by Type --</option>
                    @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type_filter') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Reset selalu muncul --}}
            <div class="flex justify-center">
                <a href="{{ route('admin.school.store') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                    Reset Filter
                </a>
            </div>
        </form>
    </div>

    {{-- School List --}}
    <div class="bg-white p-6 rounded shadow">
        {{-- Top bar --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h3 class="text-lg font-semibold md:mb-0">School List</h3>
            <div class="flex flex-col md:flex-row md:items-center gap-2">
                <form method="GET" action="{{ route('admin.school.store') }}" class="w-full md:w-auto">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search School"
                        value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-2 w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onkeydown="if(event.key === 'Enter') { this.form.submit(); }" />
                </form>

                @php $currentType = request()->segment(2); @endphp
                <a href="{{ url('admin/' . $currentType . '/export') }}"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">
                    Export Data
                </a>

            </div>
        </div>

        {{-- Filter --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <form method="GET" action="{{ route('admin.school.store') }}">
                <label for="per_page" class="mr-2">Show:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                    @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
                <span class="ml-2">entries</span>
            </form>
        </div>

        {{-- Table --}}
        @if($schools->count())
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-center">No</th>
                        <th class="px-4 py-2 border text-center">School Name</th>
                        <th class="px-4 py-2 border text-center">Category</th>
                        <th class="px-4 py-2 border text-center">Type</th>
                        <th class="px-4 py-2 border text-center">City</th>
                        <th class="px-4 py-2 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schools as $index => $school)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center text-xs">{{ $schools->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-2 border">{{ $school->school_name }}</td>
                        <td class="px-4 py-2 border text-center">{{ $school->category_name }}</td>
                        <td class="px-4 py-2 border text-center">{{ $school->type }}</td>
                        <td class="px-4 py-2 border text-center">{{ $school->city->city_name ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            <div class="flex gap-2 justify-center">
                                <button type="button"
                                    class="text-blue-500 hover:text-blue-700 text-xs"
                                    data-id="{{ $school->id }}"
                                    data-name="{{ $school->school_name }}"
                                    data-city="{{ $school->city_id }}"
                                    data-category="{{ $school->category_name }}"
                                    data-type="{{ strtoupper($school->type) }}"
                                    onclick="openEditModal(this)">
                                    ✏️ Edit
                                </button>

                                <form method="POST" action="{{ route('admin.data.delete') }}">
                                    @csrf
                                    <input type="hidden" name="table" value="schools">
                                    <input type="hidden" name="id" value="{{ $school->id }}">
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs btn-delete">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center item-center text-center">
                {{ $schools->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
        @else
        <div class="text-center text-gray-500 py-6">No School Data Found</div>
        @endif
    </div>
</div>
{{-- Modal Edit --}}
<div id="editModal" class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-xl font-semibold mb-4">Edit School</h3>
        <form method="POST" action="{{ route('admin.data.edit') }}">
            @csrf
            <input type="hidden" name="table" value="schools">
            <input type="hidden" name="id" id="edit-id">

            {{-- School Name --}}
            <div class="mb-4">
                <input type="text" name="school_name" id="edit-school_name" class="border px-3 py-2 rounded w-full" required>
            </div>

            {{-- City --}}
            <div class="mb-4">
                <select name="city_id" id="edit-city_id" class="border px-3 py-2 rounded w-full" required>
                    <option value="">-- Select City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <select name="category_name" id="edit-category_name" class="border px-3 py-2 rounded w-full" required>
                    <option value="">-- Select Category --</option>
                    @foreach(['SMA', 'SMK', 'MA'] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div class="mb-4">
                <select name="type" id="edit-type" class="border px-3 py-2 rounded w-full" required>
                    <option value="">-- Select Type --</option>
                    @foreach(['NEGERI', 'SWASTA'] as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(button) {
        document.getElementById('edit-id').value = button.dataset.id;
        document.getElementById('edit-school_name').value = button.dataset.name;

        // Set select value via JS
        const citySelect = document.getElementById('edit-city_id');
        const categorySelect = document.getElementById('edit-category_name');
        const typeSelect = document.getElementById('edit-type');

        citySelect.value = button.dataset.city;
        categorySelect.value = button.dataset.category;
        typeSelect.value = button.dataset.type.toUpperCase();


        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush
@endsection