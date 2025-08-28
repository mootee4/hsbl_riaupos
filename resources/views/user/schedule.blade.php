<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Match Schedule - SBL</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .modal-bg {
            background-color: rgba(0, 0, 0, 0.7);
        }
    </style>
</head>

<body class="bg-gray-50 py-8">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">📅 Jadwal Pertandingan SBL</h2>

        @forelse($schedules as $match)
        <div class="bg-white shadow-md rounded-lg p-4 mb-4">
            <h3 class="text-xl font-semibold">{{ $match->main_title }}</h3>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($match->upload_date)->format('d M Y') }}</p>
            @if (!empty($match->layout_image))
            <img src="{{ asset('storage/' . $match->layout_image) }}"
                class="w-24 mt-2 rounded cursor-pointer"
                data-src="{{ asset('storage/' . $match->layout_image) }}"
                onclick="showImageModal(this.dataset.src)">

            @endif

        </div>
        @empty
        <p class="text-gray-500">Belum ada jadwal pertandingan yang dipublish.</p>
        @endforelse
    </div>

    <!-- Modal Zoom -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden justify-center items-center modal-bg" onclick="handleOutsideClick(event)">
        <div class="relative max-w-full max-h-full">
            <button onclick="closeImageModal()" class="absolute top-2 right-2 text-white text-3xl font-bold z-10">&times;</button>
            <img id="modalImage" src="" class="w-64 h-64 rounded shadow-lg">
        </div>
    </div>

    <script>
        function showImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Fokus agar keyboard event bisa bekerja
            document.addEventListener('keydown', handleEscapeKey);
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Hapus event listener saat modal ditutup
            document.removeEventListener('keydown', handleEscapeKey);
        }

        // Tutup modal saat klik di luar area gambar
        function handleOutsideClick(event) {
            const modalImage = document.getElementById('modalImage');
            if (!modalImage.contains(event.target)) {
                closeImageModal();
            }
        }

        // Tutup modal saat tekan tombol ESC
        function handleEscapeKey(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        }
    </script>

</body>

</html>