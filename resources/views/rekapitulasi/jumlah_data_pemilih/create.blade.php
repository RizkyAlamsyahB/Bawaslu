@extends('layouts.app')

@section('title', 'Tambah Jumlah Pemilih')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Jumlah Pemilih</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Tambah Jumlah Pemilih</h2>
        <p class="section-lead">
            Halaman ini memungkinkan Anda untuk menambahkan data jumlah pemilih baru.
        </p>

        <div class="card">
            <!-- Menampilkan alert error validasi jika ada -->
            @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

            <div class="card-header">
                <h4>Formulir Jumlah Pemilih</h4>
            </div>
            <div class="card-body">
                <!-- Session Alert -->
                <!-- @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif -->

                
                <form id="jumlahPemilihForm" action="{{ route('jumlah_data_pemilih.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <select class="form-control" id="tipe_pemilihan" name="tipe_pemilihan" required>
                            <option value="">Pilih Tipe Pemilihan</option>
                            <option value="gubernur" {{ old('tipe_pemilihan') == 'gubernur' ? 'selected' : '' }}>Gubernur</option>
                            <option value="walikota" {{ old('tipe_pemilihan') == 'walikota' ? 'selected' : '' }}>Walikota</option>
                        </select>
                        @error('tipe_pemilihan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="laki_laki">Laki-laki</label>
                        <input type="number" class="form-control" id="laki_laki" name="laki_laki" 
                               value="{{ old('laki_laki') }}" oninput="calculateSum()" required>
                        @error('laki_laki')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="perempuan">Perempuan</label>
                        <input type="number" class="form-control" id="perempuan" name="perempuan" 
                               value="{{ old('perempuan') }}" oninput="calculateSum()" required>
                        @error('perempuan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah">Jumlah Yang Diinputkan</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       value="{{ old('jumlah') }}" required>
                                @error('jumlah')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="calculated_jumlah">Jumlah Yang Harus Diinputkan</label>
                                <input type="number" class="form-control bg-light" id="calculated_jumlah" 
                                       readonly disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-left">
                        <button type="submit" class="btn btn-warning" onclick="return validateForm()">Simpan</button>
                        <a href="{{ route('jumlah_data_pemilih.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function calculateSum() {
        const lakiLaki = parseInt(document.getElementById('laki_laki').value) || 0;
        const perempuan = parseInt(document.getElementById('perempuan').value) || 0;
        const calculatedJumlah = lakiLaki + perempuan;
        
        // Update calculated_jumlah
        document.getElementById('calculated_jumlah').value = calculatedJumlah;
    }

    function validateForm() {
    const jumlah = parseInt(document.getElementById('jumlah').value);
    const calculatedJumlah = parseInt(document.getElementById('calculated_jumlah').value);

    if (jumlah !== calculatedJumlah) {
        // Tampilkan alert Bootstrap jika jumlah tidak sesuai
        document.getElementById('jumlahAlert').classList.remove('d-none');
        return false; // Mencegah form dari pengiriman
    }
    
    // Sembunyikan alert jika jumlah sesuai
    document.getElementById('jumlahAlert').classList.add('d-none');
    return true; // Mengizinkan form untuk disubmit jika jumlah sesuai
}

</script>
@endpush
