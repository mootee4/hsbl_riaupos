@extends('user.layouts.app')

@section('title', 'Videos')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<main class="max-w-7xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-extrabold text-center mb-8">VIDEOS</h1>

  {{-- Filter Tabs + Searchbar --}}
  <form method="GET" action="{{ route('user.videos') }}"
        class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div class="flex flex-wrap items-center gap-4 mb-4 md:mb-0">
      @php $activeAll = request('type', '') === ''; @endphp
      <a href="{{ route('user.videos') }}">
        <button type="button"
          class="px-5 py-2 rounded-md text-base font-normal transition
                 {{ $activeAll ? 'bg-[#71BBB2] text-white' : 'bg-white text-black' }}
                 hover:bg-[#71BBB2] hover:text-white">
          Semua
        </button>
      </a>
      @php $activeVideo = request('type') === 'video'; @endphp
      <a href="{{ route('user.videos', ['type' => 'video']) }}">
        <button type="button"
          class="px-5 py-2 rounded-md text-base font-normal transition
                 {{ $activeVideo ? 'bg-[#71BBB2] text-white' : 'bg-white text-black' }}
                 hover:bg-[#71BBB2] hover:text-white">
          Video
        </button>
      </a>
      @php $activeLive = request('type') === 'live'; @endphp
      <a href="{{ route('user.videos', ['type' => 'live']) }}">
        <button type="button"
          class="px-5 py-2 rounded-md text-base font-normal transition
                 {{ $activeLive ? 'bg-[#71BBB2] text-white' : 'bg-white text-black' }}
                 hover:bg-[#71BBB2] hover:text-white">
          Live
        </button>
      </a>
    </div>
    <div class="ml-auto flex items-center gap-2">
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Telusuri"
        class="bg-white shadow-md px-5 py-2 text-base font-normal rounded-md focus:outline-none"
      />
      <button type="submit" aria-label="Search" class="text-black text-xl">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </form>

  {{-- Daftar Video --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @forelse($videos as $video)
      <div class="bg-white rounded-md overflow-hidden shadow hover:shadow-lg transition">
        <a href="{{ route('user.videos.detail', $video->slug) }}">
          {{-- Wrapper 16:9 --}}
          <div class="relative w-full" style="padding-top:56.25%;">
            <img
              src="{{ asset($video->thumbnail) }}"
              alt="{{ $video->title }}"
              class="absolute top-0 left-0 w-full h-full object-cover"
            />
          </div>
        </a>
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-1">
            <a href="{{ route('user.videos.detail', $video->slug) }}">
              {{ Str::limit($video->title, 50) }}
            </a>
          </h3>
          <p class="text-sm text-gray-500">{{ $video->created_at->format('d M Y') }}</p>
        </div>
      </div>
    @empty
      <p class="col-span-3 text-center text-gray-600">
        Tidak ada video ditemukan.
      </p>
    @endforelse
  </div>

  {{-- Pagination Centered --}}
  <div class="w-full mt-10 mb-16 flex justify-center">
    <div class="inline-block">
      {{ $videos->withQueryString()->links() }}
    </div>
  </div>
</main>
@endsection
