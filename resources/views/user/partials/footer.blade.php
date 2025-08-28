<footer class="max-w-7xl mx-auto mt-12 bg-white rounded-t-[40px] shadow-inner py-8 px-6">
  <div class="mb-6">

    @foreach($groupedSponsors as $category => $sponsors)
      <div class="mb-4">
        <h4 class="font-semibold text-sm uppercase mb-4 text-center">
          {{ $category }}
        </h4>
        {{-- Urutan kanan ke kiri, tapi tampil dari lama ke baru --}}
        <div class="flex flex-row justify-center gap-6">
          @foreach($sponsors->sortBy('created_at') as $sponsor)
            @php
              $imgClass = match($category) {
                'Presented by' => 'h-16',
                'Official Partners', 'Official Suppliers', 'Supporting Partners' => 'h-12',
                'Managed by' => 'h-16',
                default => 'h-12',
              };
            @endphp
            <a href="{{ $sponsor->sponsors_web }}" target="_blank" class="flex items-center">
              <img
                src="{{ asset('uploads/sponsors/' . $sponsor->logo) }}"
                alt="{{ $sponsor->sponsor_name }}"
                class="{{ $imgClass }} object-contain"
              />
            </a>
          @endforeach
        </div>
      </div>
    @endforeach

  </div>

  <div class="text-center text-xs text-gray-600">
    &copy; 2025 Riau Pos - Honda HSBL All Rights Reserved<br>
    Developed with ❤️ by : Mutia Rizkianti | Wafiq Wardatul Khairani
  </div>
</footer>
