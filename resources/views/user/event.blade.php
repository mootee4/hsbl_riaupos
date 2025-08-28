<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page - SBL</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">🏀 Upcoming SBL Event</h1>
            <p class="text-gray-600 mt-2">Check if there’s a registration open!</p>
        </div>

        @if($activeEvents->count() > 0)
        @foreach($activeEvents as $event)
        <div class="bg-white rounded-lg shadow-md p-6 max-w-xl mx-auto text-center border border-green-300 mb-6">
            <h2 class="text-2xl font-semibold text-green-700">{{ $event->event_name }}</h2>
            <p class="text-gray-600 mt-2">
                {{ \Carbon\Carbon::parse($event->start_date . ' ' . $event->start_time, 'UTC')->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                &mdash;
                {{ \Carbon\Carbon::parse($event->end_date . ' ' . $event->end_time, 'UTC')->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
            </p>

            <a href="{{ route('user.event.register', ['id' => $event->id]) }}"
                class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                Register Now
            </a>
        </div>
        @endforeach
        @else
        <div class="text-center text-gray-500 mt-20">
            <p class="text-xl">😢 No active events at the moment.</p>
            <p class="mt-2">Please check back later for new events.</p>
        </div>
        @endif

        <a href="{{ route('user.schedule') }}" class="text-blue-500 underline">Lihat Jadwal Pertandingan</a>


    </div>
</body>

</html>