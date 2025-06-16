{{-- [CDN] --}}
{{-- Data Tables --}}
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

{{-- Sweet Alert Confirm Delete --}}
<script>
    function confirmDelete(button) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#919191',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Cari form terdekat dan submit
                button.closest('form').submit();
            }
        });
    }
</script>
