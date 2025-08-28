@extends('admin.layout')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'award'; @endphp
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
    <h2 class="text-2xl font-bold mb-6">Award Data</h2>
    <div class="bg-white p-6 rounded shadow mb-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-md">Add School</h3>
        </div>
        <form action="{{ route('admin.award.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="award_type" placeholder="Award Type" class="border rounded px-3 py-2 w-full">
                <input type="text" name="category" placeholder="Award Category" class="border rounded px-3 py-2 w-full">
            </div>

            <div class="flex justify-center pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Add Award
                </button>
            </div>
        </form>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h3 class="text-lg font-semibold">Award List</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
            @foreach ([
            'Award Type' => $awardTypes->filter(), // filter untuk buang nilai kosong
            'Award Category' => $awardCategories->filter(),
            ] as $label => $collection)
            @php
            $typeKey = $label === 'Award Type' ? 'award_type' : 'category';
            @endphp

            @if($collection->count())
            <div class="border rounded p-4 bg-gray-50">
                <h4 class="font-bold text-sm mb-2">{{ $label }}</h4>
                <ul class="text-sm text-gray-700 space-y-1 max-h-[9999px]">
                    @foreach ($collection as $value)
                    <li class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span>{{ $value }}</span>
                        </div>
                        <div class="flex gap-2">
                            {{-- Edit --}}
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-700 text-xs"
                                onclick="openEditModal('{{ $typeKey }}', '{{ $value }}')">
                                ✏️
                            </button>
                            <form method="POST" action="{{ route('admin.data.delete') }}">
                                @csrf
                                <input type="hidden" name="table" value="awards">
                                <input type="hidden" name="field" value="{{ $typeKey }}">
                                <input type="hidden" name="value" value="{{ $value }}">
                                <button type="button" class="text-red-500 hover:text-red-700 text-xs btn-delete">🗑️</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="border rounded p-4 bg-gray-50">
                <h4 class="font-bold text-sm mb-2">{{ $label }}</h4>
                <p class="text-sm text-red-500">No {{ strtolower($label) }} data</p>
            </div>
            @endif
            @endforeach

        </div>

    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Edit Award</h3>
        <form id="editForm" method="POST" action="{{ route('admin.data.edit') }}">
            @csrf
            <input type="hidden" name="table" value="awards">
            <input type="hidden" name="type" id="editType">
            <input type="hidden" name="original_value" id="originalValue">

            <input type="text" name="new_value" id="newValueInput"
                class="border rounded w-full px-3 py-2 mb-4" placeholder="New Value">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="text-gray-600 hover:underline">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    function openEditModal(type, value) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editType').value = type;
        document.getElementById('originalValue').value = value;
        document.getElementById('newValueInput').value = value;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance.navigation.type === 2) {
            const form = document.querySelector("form[action='{{ route('admin.award.store') }}']");
            if (form) form.reset();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endpush

@endsection