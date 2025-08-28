@extends('layouts.app')

@section('title', 'News')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<main class="max-w-7xl mx-auto px-4 py-8">

  <h1 class="text-3xl font-extrabold text-center mb-8">NEWS</h1>

  {{-- Search & Filter Series --}}
  <form method="GET" action="{{ route('news.index') }}" class="flex justify-center mb-10 space-x-4">
    {{-- Search --}}
    <input
      type="text"
      name="search"
      value="{{ request('search') }}"
      placeholder="Search..."
      class="w-1/3 border border-gray-300 rounded px-4 py-2 shadow-sm focus:ring-[#3b7d7a] focus:outline-none"
    >

    {{-- Filter Series --}}
    <select
      name="series"
      class="w-1/4 border border-gray-300 rounded px-4 py-2 shadow-sm focus:ring-[#3b7d7a] focus:outline-none"
    >
      <option value="">All Series</option>
      @foreach($seriesList as $series)
        <option value="{{ $series }}" @selected(request('series') === $series)>{{ $series }}</option>
      @endforeach
    </select>

    {{-- Submit --}}
    <button
      type="submit"
      class="bg-[#3b7d7a] text-white px-5 py-2 rounded hover:bg-[#2f605e] transition"
    >
      Apply
    </button>
  </form>

  {{-- Grid Berita --}}
  <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    @forelse($news as $item)
      <article class="border border-[#3b7d7a] bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition">
        <a href="{{ route('news.show', $item->id) }}">
          {{-- Gambar berita atau placeholder --}}
          <img
            src="{{ $item->image
                    ? asset($item->image)
                    : asset('images/default_news.jpg') }}"
            alt="{{ $item->title }}"
            class="w-full h-44 object-cover"
          >

          <div class="p-4">
            <h2 class="font-extrabold text-lg mb-1">{{ Str::limit($item->title, 50) }}</h2>
            <p class="text-sm mb-2 truncate">
              {{ Str::limit(strip_tags($item->content), 60) }}
            </p>
            <span class="text-xs text-gray-500">
              {{ $item->created_at->format('d M Y') }}
            </span>
            <div class="mt-2 text-[#3b7d7a] font-semibold text-sm hover:underline">
              Read More &gt;
            </div>
          </div>
        </a>
      </article>
    @empty
      <p class="text-center text-gray-600 col-span-full">No news found.</p>
    @endforelse
  </section>

  {{-- Pagination --}}
  <div class="flex justify-center mt-10 mb-16">
    {{ $news->appends(request()->except('page'))->links() }}
  </div>

</main>
@endsection
