{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Flash Message Handling --}}
@php
$alertTypes = array(
'success' => array('icon' => 'success', 'title' => 'Sukses!', 'color' => '#3085d6'),
'warning' => array('icon' => 'warning', 'title' => 'Peringatan!', 'color' => '#f59e0b'),
'error' => array('icon' => 'error', 'title' => 'Oops...', 'color' => '#d33')
);
@endphp

@foreach ($alertTypes as $type => $config)
@if(session()->has($type))
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (performance.navigation.type !== 2) { // Prevent on browser back
      Swal.fire({
        icon: '{{ $config["icon"] }}',
        title: '{{ $config["title"] }}',
        text: "{{ e(session($type)) }}",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        confirmButtonColor: '{{ $config["color"] }}'
      });
    }
  });
</script>
{{-- Force forget session flash message after use --}}
@php session()->forget($type); @endphp
@endif
@endforeach

{{-- Delete Confirmation Handling --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();
        const form = button.closest('form');
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: 'Data yang dihapus tidak bisa dikembalikan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then(result => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-done').forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();
        const form = button.closest('form');
        Swal.fire({
          title: 'Tandai match ini sebagai selesai?',
          text: 'Setelah Anda menandai pertandingan ini sebagai selesai, jadwal tidak akan lagi ditampilkan di halaman utama pengguna, dan data pertandingan tidak dapat diubah kembali.',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#aaa',
          confirmButtonText: 'Ya, selesai!',
          cancelButtonText: 'Batal'
        }).then(result => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>