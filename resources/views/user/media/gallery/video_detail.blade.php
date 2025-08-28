@extends('user.layouts.app')

@section('title', $video->title)

@section('content')
<main class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

  {{-- Video Player & Info --}}
  <div class="lg:col-span-2 space-y-6">
    {{-- Embed YouTube --}}
    @if($video->youtube_link)
      <div class="relative w-full" style="padding-top:56.25%;">
        <iframe
          src="https://www.youtube.com/embed/{{ $video->youtube_link }}"
          title="{{ $video->title }}"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          class="absolute top-0 left-0 w-full h-full rounded-md shadow-lg"
        ></iframe>
      </div>
    @else
      <div class="text-red-600 font-medium">
        Video tidak valid atau link YouTube salah.
      </div>
    @endif

    {{-- Video Title & Meta --}}
    <h1 class="text-2xl font-bold">{{ $video->title }}</h1>
    <p class="text-sm text-gray-500">Diunggah pada {{ $video->created_at->format('d M Y') }}</p>

    {{-- Description --}}
    @if($video->description)
      <div class="prose max-w-none">
        {!! nl2br(e($video->description)) !!}
      </div>
    @endif
  </div>

  {{-- Sidebar: Video Lainnya --}}
  <aside class="space-y-4">
    <h2 class="text-xl font-semibold mb-2">Video Lainnya</h2>
    <ul class="space-y-4">
      @foreach($others as $other)
        <li class="flex items-center space-x-4">
          <a href="{{ route('user.videos.detail', $other->slug) }}">
            <img
              src="{{ asset($other->thumbnail) }}"
              alt="{{ $other->title }}"
              class="w-20 h-12 object-cover rounded-md shadow-sm"
            />
          </a>
          <div>
            <a href="{{ route('user.videos.detail', $other->slug) }}"
               class="font-medium hover:text-[#71BBB2]">
              {{ \Illuminate\Support\Str::limit($other->title, 40) }}
            </a>
            <p class="text-xs text-gray-400">{{ $other->created_at->format('d M Y') }}</p>
          </div>
        </li>
      @endforeach
    </ul>
  </aside>

</main>
@endsection
