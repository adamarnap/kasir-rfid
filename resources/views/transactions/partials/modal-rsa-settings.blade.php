{{-- Modal RSA Settings --}}
<div class="modal fade" id="rsaSettingsModal" tabindex="-1" aria-labelledby="rsaSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="rsaSettingsModalLabel">
                    <i class="bi bi-shield-lock"></i> Pengaturan Enkripsi RSA
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rsaSettingsForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informasi:</strong> Pengaturan ini mengatur kunci enkripsi RSA untuk melindungi data transaksi. 
                        Pastikan Anda memahami nilai yang diinputkan.
                    </div>

                    <div class="mb-3">
                        <label for="rsa_n" class="form-label">
                            <strong>n (Modulus)</strong>
                            <small class="text-muted">- Hasil perkalian dua bilangan prima (p × q)</small>
                        </label>
                        <input type="text" class="form-control" id="rsa_n" name="n" required 
                               placeholder="Masukkan nilai n">
                        <div class="invalid-feedback">
                            Nilai n harus diisi.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rsa_d" class="form-label">
                            <strong>d (Private Exponent)</strong>
                            <small class="text-muted">- Kunci privat untuk dekripsi</small>
                        </label>
                        <input type="text" class="form-control" id="rsa_d" name="d" required 
                               placeholder="Masukkan nilai d">
                        <div class="invalid-feedback">
                            Nilai d harus diisi.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="rsa_e" class="form-label">
                            <strong>e (Public Exponent)</strong>
                            <small class="text-muted">- Kunci publik untuk enkripsi</small>
                        </label>
                        <input type="text" class="form-control" id="rsa_e" name="e" required 
                               placeholder="Masukkan nilai e">
                        <div class="invalid-feedback">
                            Nilai e harus diisi.
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Mengubah kunci RSA akan mempengaruhi kemampuan untuk mendekripsi data transaksi yang sudah ada. 
                        Lakukan dengan hati-hati!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveRsaSettings">
                        <i class="bi bi-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Load RSA settings when modal is opened
        $('#rsaSettingsModal').on('show.bs.modal', function() {
            $.ajax({
                url: '{{ route("transactions.rsa-settings.get") }}',
                method: 'GET',
                success: function(response) {
                    $('#rsa_n').val(response.n);
                    $('#rsa_d').val(response.d);
                    $('#rsa_e').val(response.e);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat pengaturan RSA',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle form submission
        $('#rsaSettingsForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const submitBtn = $('#saveRsaSettings');
            const originalBtnText = submitBtn.html();
            
            // Disable button and show loading
            submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Menyimpan...');

            $.ajax({
                url: '{{ route("transactions.rsa-settings.update") }}',
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#rsaSettingsModal').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Gagal menyimpan pengaturan RSA';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    // Re-enable button
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });
    });
</script>
@endpush
