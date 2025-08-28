@extends('user.layouts.app')

@section('title', 'Schedules & Results')

@php
    use App\Models\EventData;
    use Illuminate\Support\Carbon;
@endphp

@section('content')
<main class="max-w-7xl mx-auto px-4 py-8 pt-24 relative">

    <h1 class="text-3xl font-extrabold text-center mb-8">SCHEDULES & RESULTS</h1>

    <div class="border bg-white rounded-lg shadow-sm">
        {{-- Tab bar --}}
        <div class="border-b px-6 py-4 flex space-x-4 justify-left">
            <button id="btn-schedule" onclick="showTab('schedule')" class="px-4 py-2 rounded-t-lg hover:bg-gray-100 hover:text-blue-600 transition focus:outline-none">Schedule</button>
            <button id="btn-result" onclick="showTab('result')" class="px-4 py-2 rounded-t-lg hover:bg-gray-100 hover:text-blue-600 transition focus:outline-none">Result</button>
        </div>

        {{-- Tab content --}}
        <div class="px-6 py-6">
            {{-- SCHEDULE TAB --}}
            <div id="tab-schedule" class="tab-content">
                @if ($schedules->count())
                    <div class="flex flex-col items-center space-y-10">
                        @foreach ($schedules as $item)
                            <div class="text-center max-w-md">
                                <p class="text-sm text-gray-500 mb-1">{{ \Carbon\Carbon::parse($item->upload_date)->format('d M Y') }}</p>
                                <h3 class="text-base font-semibold mb-2">{{ $item->main_title }}</h3>
                                @if (!empty($item->layout_image))
                                    <img 
                                        src="{{ asset($item->layout_image) }}" 
                                        alt="Schedule Image" 
                                        class="w-full h-auto max-w-xs mx-auto cursor-pointer rounded shadow hover:shadow-md transition"
                                        onclick="openImageModal('{{ asset($item->layout_image) }}')">
                                @else
                                    <p class="text-gray-400 italic">Tidak ada gambar jadwal.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">Belum ada jadwal pertandingan.</p>
                @endif
            </div>

            {{-- RESULT TAB --}}
            <div id="tab-result" class="tab-content hidden">
                @if ($results->count())
                    <div class="grid gap-4">
                        @foreach ($results as $match)
                            <div class="border border-white bg-white rounded-lg p-4 shadow hover:shadow-lg transition">
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</p>
                                <p class="text-lg font-bold">
                                    {{ $match->team1->school_name ?? '-' }}
                                    {{ $match->score_1 ?? '-' }} - {{ $match->score_2 ?? '-' }}
                                    {{ $match->team2->school_name ?? '-' }}
                                </p>
                                @if(!empty($match->scoresheet))
                                    <a href="{{ Storage::url($match->scoresheet) }}" target="_blank" class="text-blue-600 underline text-sm mt-2 inline-block">Lihat Scoresheet</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $results->links('pagination::tailwind') }}
                    </div>
                @else
                    <p class="text-gray-500">Belum ada hasil pertandingan.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Floating Register Button --}}
    @php
        $now = Carbon::today();
        $hasActive = EventData::whereDate('start_date', '<=', $now)
                              ->whereDate('end_date', '>=', $now)
                              ->exists();
    @endphp

    @if($hasActive)
    <div x-data="registerButton()" class="fixed bottom-8 right-8 z-50 group" @mouseenter="hover = true" @mouseleave="hover = false">
      <button
          @click="go(); explode = true; setTimeout(() => explode = false, 1000)"
          :class="explode ? 'animate-pulse scale-110' : 'animate-wiggle'"
          class="relative px-6 py-3 rounded-full shadow-xl text-white font-extrabold focus:outline-none overflow-visible transition-all duration-300 ease-in-out"
          style="background: linear-gradient(45deg, #FF6B6B, #FFD93D);"
      >
        <span x-show="!explode">Register Now! 🎉</span>
        <span x-show="explode">✨🎆✨</span>
      </button>

      {{-- Confetti saat hover --}}
      <template x-if="hover && !explode">
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
          <template x-for="i in 10" :key="i">
            <div :style="`--i:${i}`" class="confetti-burst w-2 h-2 rounded-full absolute"></div>
          </template>
        </div>
      </template>

      {{-- Confetti saat klik --}}
      <template x-if="explode">
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
          <template x-for="n in pieces" :key="n">
            <div :style="`--i:${n}`" class="confetti-drop w-2 h-2 bg-yellow-400 rounded-full absolute"></div>
          </template>
        </div>
      </template>
    </div>
    @endif

        {{-- Floating Download SnK Button --}}
    @if($terms->count())
    <a href="{{ route('user.download_terms') }}"
       class="fixed bottom-6 left-6 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-5 py-3 rounded-full shadow-lg flex items-center space-x-2 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        <span>Download SnK</span>
    </a>
    @endif

{{-- Modal for full image --}}
<div id="imageModal"
     class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-80 hidden">
    <div class="relative max-w-4xl mx-auto">
        <button onclick="closeImageModal()"
                class="absolute top-0 right-0 mt-4 mr-4 text-white text-4xl font-bold z-50 hover:text-red-400 transition">
            &times;
        </button>
        <img id="modalImage" src="" alt="Full Image"
             class="max-h-[90vh] w-auto mx-auto rounded shadow-lg">
    </div>
</div>
</main>


@endsection

@push('styles')
<style>
  @keyframes wiggle {
    0%,100% { transform: rotate(-3deg); }
    50% { transform: rotate(3deg); }
  }
  .animate-wiggle { animation: wiggle 1s ease-in-out infinite; }

  .confetti-drop {
    top: -10px;
    left: 50%;
    animation: fall 1s ease-in-out forwards;
    animation-delay: calc(var(--i) * 0.05s);
  }
  @keyframes fall {
    0%   { transform: translateX(0) translateY(0) scale(1) rotate(0deg); opacity:1; }
    100% { transform: translateX(calc((var(--i) - 5) * 20px)) translateY(200px) scale(0.5) rotate(720deg); opacity:0; }
  }

  .confetti-burst {
    width: 6px;
    height: 6px;
    background-color: hsl(calc(var(--i) * 36), 100%, 50%);
    opacity: 0.9;
    animation: burst 0.8s ease-out forwards;
    transform-origin: center;
  }
  @keyframes burst {
    0% {
      transform: translateY(0) scale(1) rotate(0deg);
      opacity: 1;
    }
    100% {
      transform: 
        translateX(calc((var(--i) - 5) * 14px)) 
        translateY(calc(-60px - (var(--i) * 2px))) 
        scale(1.4) rotate(720deg);
      opacity: 0;
    }
  }
</style>
@endpush

@push('scripts')
<script>
function showTab(tab) {
  document.getElementById('tab-schedule').classList.toggle('hidden', tab !== 'schedule');
  document.getElementById('tab-result').classList.toggle('hidden', tab !== 'result');
  document.getElementById('btn-schedule').classList.toggle('bg-green-600', tab === 'schedule');
  document.getElementById('btn-schedule').classList.toggle('text-white', tab === 'schedule');
  document.getElementById('btn-result').classList.toggle('bg-green-600', tab === 'result');
  document.getElementById('btn-result').classList.toggle('text-white', tab === 'result');
}

function openImageModal(src) {
  document.getElementById('modalImage').src = src;
  document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
  document.getElementById('imageModal').classList.add('hidden');
  document.getElementById('modalImage').src = '';
}
document.getElementById('imageModal').addEventListener('click', e => {
  if (e.target.id === 'imageModal') closeImageModal();
});

 function registerButton() {
    return {
      explode: false,
      hover: false,
      pieces: Array.from({length: 10}, (_,i)=>i),
      go() {
        // Ganti '/your-register-page' dengan rute atau URL yang mau dibuka
        window.open('/your-register-page', '_blank');
      }
    }
  }
document.addEventListener('DOMContentLoaded', () => {
  showTab('schedule');
  document.querySelectorAll('nav a').forEach(a => {
    if (a.textContent.trim() === 'Schedules & Results') {
      a.classList.add('text-teal-600','font-bold');
    }
  });
});
</script>
@endpush
