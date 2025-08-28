@php
    $labels = [
        'data'        => 'Data',
        'school'      => 'School',
        'venue'       => 'Venue',
        'award'       => 'Award',
        'camper'      => 'Camper',
        'competition' => 'Competition',
        'phase'       => 'Phase',
        'series'      => 'Series',
    ];
    $label = $labels[$type] ?? ucfirst($type);
@endphp

<a href="{{ route('admin.admin.export', ['type' => $type]) }}"
   class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
   Export {{ $label }}
</a>
