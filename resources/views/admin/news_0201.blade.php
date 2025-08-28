@extends('layouts.app')

@section('title', $item->title)

@section('content')
<main class="max-w-5xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8 py-8">

  {{-- Konten Utama --}}
  <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-8">
    <h1 class="text-3xl font-bold mb-4">{{ $item->title }}</h1>
    <p class="text-sm text-gray-500 mb-6">
      {{ $item->created_at->format('d M Y') }} &middot; {{ $item->series }}
    </p>

    @if($item->image)
      <img src="{{ asset($item->image) }}"
           alt="{{ $item->title }}"
           class="w-full aspect-[16/9] object-cover rounded mb-6">
    @endif

    <div class="prose max-w-none">
      {!! nl2br(e($item->content)) !!}
    </div>

    <a href="{{ route('news.index') }}" class="inline-block mt-6 text-[#3b7d7a] hover:underline">
      &larr; Back to News
    </a>
  </div>

  {{-- Sidebar: Saran Berita Terbaru --}}
  <aside class="lg:col-span-1 space-y-6">
    <h2 class="text-xl font-bold mb-4">Berita Terbaru</h2>
    @foreach($recentNews as $newsItem)
      <a href="{{ route('news.show', $newsItem->id) }}"
         class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
        <h3 class="font-semibold text-lg">{{ \Illuminate\Support\Str::limit($newsItem->title, 50) }}</h3>
        <p class="text-xs text-gray-500 mt-1">
          {{ $newsItem->created_at->format('d M Y') }} &middot;
          <span class="italic">{{ $newsItem->series }}</span>
        </p>
      </a>
    @endforeach
  </aside>

</main>
@endsection
