@php
    // Tentukan tab aktif lewat variabel $activeTab
    $tabs = [
        'data'        => ['label' => 'Data',        'route' => 'admin.all_data'],
        'city'        => ['label' => 'City',        'route' => 'admin.all_data_city'],
        'school'      => ['label' => 'School',      'route' => 'admin.all_data_school'],
        'venue'       => ['label' => 'Venue',       'route' => 'admin.all_data_venue'],
        'award'       => ['label' => 'Award',       'route' => 'admin.all_data_award'],

    ];
@endphp

<div class="bg-white rounded shadow-sm mb-0">
<nav class="flex w-full bg-white shadow-sm z-50">
    @foreach($tabs as $key => $tab)
        @php $isActive = ($activeTab === $key); @endphp
        <a
            href="{{ route($tab['route']) }}"
            class="flex-1 text-center py-3 font-medium transition-colors
                {{ $isActive
                    ? 'border-b-4 border-blue-600 text-blue-600 bg-blue-50'
                    : 'border-b-4 border-transparent text-gray-600 hover:bg-gray-100' }}"
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
</div>
