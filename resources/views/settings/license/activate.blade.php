<form method="POST" action="{{ route('license.activate.submit') }}">
    @csrf
    <label for="license_key">Masukkan License Key:</label>
    <input type="text" name="license_key" required>
    <button type="submit">Aktifkan</button>
</form>
