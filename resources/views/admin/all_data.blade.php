@extends('admin.layout')
@section('title', 'Master Data - Administrator')
@section('content')

{{-- set active tab jadi "data" --}}
@php $activeTab = 'data'; @endphp

{{-- include partial tabs --}}
@include('partials.tabs', compact('activeTab'))

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
@endpush


<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">Data</h2>
    {{-- Add Data Card --}}
    <div class="bg-white p-6 rounded shadow mt-2">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add Data</h3>
        </div>

        {{-- Form Simpan Data --}}
        <form method="POST" action="{{ route('admin.data.store') }}" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block font-semibold mb-1">Season</label>
                    <input type="text" name="season_name"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Series</label>
                    <input type="text" name="series_name"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Competition</label>
                    <input type="text" name="competition"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-semibold mb-1">Competition Type</label>
                    <input type="text" name="competition type"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Regional, National, International">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Phase</label>
                    <input type="text" name="phase"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit Data
                </button>
            </div>
        </form>
    </div>


    {{-- List Data --}}
    <div class="bg-white p-6 rounded shadow mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">List Data</h3>
        </div>

        {{-- Baris Pertama: 3 kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @foreach ([
            'season' => $seasons,
            'series' => $series,
            'competition' => $competitions,
            ] as $type => $collection)
            <div class="border rounded-lg p-4 bg-gray-50">
                <h4 class="font-bold text-sm text-gray-800 mb-3 uppercase tracking-wide">
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </h4>

                @if($collection->count())
                <ul class="space-y-2 text-sm text-gray-700 max-h-64 overflow-y-auto pr-1">
                    @foreach ($collection as $value)
                    <li class="flex justify-between items-center">
                        <span class="truncate w-4/5">{{ $value }}</span>
                        <div class="flex gap-2">
                            {{-- Edit --}}
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-700 text-xs"
                                onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                ✏️
                            </button>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.data.delete') }}" class="delete-form">
                                @csrf
                                <input type="hidden" name="table" value="add_data">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="selected[]" value="{{ $value }}">
                                <button type="button" class="text-red-500 hover:text-red-700 text-xs btn-delete">🗑️</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-red-500 italic">No {{ $type }} data</p>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Baris Kedua: 2 kolom tapi tampilannya sama kayak di atas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            @foreach ([
            'competition type' => $competition_types ?? collect(),
            'phase' => $phases,
            ] as $type => $collection)
            <div class="border rounded-lg p-4 bg-gray-50">
                <h4 class="font-bold text-sm text-gray-800 mb-3 uppercase tracking-wide">
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </h4>

                @if($collection->count())
                <ul class="space-y-2 text-sm text-gray-700 max-h-64 overflow-y-auto pr-1">
                    @foreach ($collection as $value)
                    <li class="flex justify-between items-center">
                        <span class="truncate w-4/5">{{ $value }}</span>
                        <div class="flex gap-2">
                            {{-- Edit --}}
                            <button
                                type="button"
                                class="text-blue-500 hover:text-blue-700 text-xs"
                                onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                ✏️
                            </button>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.data.delete') }}" class="delete-form">
                                @csrf
                                <input type="hidden" name="table" value="add_data">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="hidden" name="selected[]" value="{{ $value }}">
                                <button type="button" class="text-red-500 hover:text-red-700 text-xs btn-delete">🗑️</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-red-500 italic">No {{ $type }} data</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>


</div>

{{-- Modal Edit --}}
<div id="editModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow w-96 relative">
        <h3 class="text-lg font-semibold mb-4">Edit Data</h3>
        <form id="editForm" method="POST" action="{{ route('admin.data.edit') }}">
            @csrf
            <input type="hidden" name="table" value="add_data">
            <input type="hidden" name="type" id="editType">
            <input type="hidden" name="old_value" id="editOldValue">
            <label class="block font-semibold mb-1">New Value</label>
            <input type="text" name="new_value" id="editNewValue" class="w-full border px-3 py-2 mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="text-gray-600">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Scripts --}}
<script>
    function openEditModal(type, oldValue) {
        document.getElementById('editType').value = type;
        document.getElementById('editOldValue').value = oldValue;
        document.getElementById('editNewValue').value = oldValue;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

@push('scripts')
@include('partials.sweetalert')
@endpush
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection