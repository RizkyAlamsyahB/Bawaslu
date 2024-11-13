@extends('layouts.app')

@section('title', 'Form Wizard Data Pemilihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Form Wizard Data Pemilihan {{ $tipePemilihan->nama }}</h1>
        </div>

        <div class="section-body">
            <!-- Progress bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: {{ ($currentStep / 10) * 100 }}%"
                            aria-valuenow="{{ $currentStep }}" aria-valuemin="0" aria-valuemax="10">
                            Step {{ $currentStep }} dari 10
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Progress: {{ number_format(($currentStep / 10) * 100, 0) }}%</span>
                        <span>Step {{ $currentStep }}/10</span>
                    </div>
                </div>
            </div>
            <!-- Form Card -->
            <div class="card">
                <div class="card-header">
                    <h4>
                        @switch($currentStep)
                            @case(1)
                                Form Input Data Jumlah Pemilih DPT
                            @break

                            @case(2)
                                Form Input Data Pengguna Hak Pilih DPT
                            @break

                            @case(3)
                                Form Input Data Pengguna Hak Pilih DPTB
                            @break

                            @case(4)
                                Form Input Data Pengguna Hak Pilih DPK
                            @break

                            @case(5)
                                Form Input Data Jumlah Pengguna Hak Pilih
                            @break

                            @case(6)
                                Form Input Data Penggunaan Surat Suara
                            @break

                            @case(7)
                                Form Input Data Pemilih Disabilitas
                            @break

                            @case(8)
                                Form Input Data Rincian Perolehan Suara Pasangan Calon
                            @break

                            @case(9)
                                Form Input Data Suara Sah dan Tidak Sah
                            @break

                            @case(10)
                                Form Input Uraian Hasil Pengawasan
                            @break
                        @endswitch
                    </h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form action="{{ route('wizard.store') }}" method="POST" onsubmit="return validateForm()">
                        @csrf
                        <input type="hidden" name="tipe_pemilihan_id" value="{{ $tipePemilihan->id }}">

                        @if (($currentStep >= 1 && $currentStep <= 5) || $currentStep == 7)
                            <!-- Form untuk step 1-5 & 7 (form standar laki-laki, perempuan, jumlah) -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Laki-laki</label>
                                        <input type="number" class="form-control @error('laki_laki') is-invalid @enderror"
                                            name="laki_laki" id="laki_laki" value="{{ old('laki_laki') }}"
                                            oninput="calculateSum()" required min="0">
                                        @error('laki_laki')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Perempuan</label>
                                        <input type="number" class="form-control @error('perempuan') is-invalid @enderror"
                                            name="perempuan" id="perempuan" value="{{ old('perempuan') }}"
                                            oninput="calculateSum()" required min="0">
                                        @error('perempuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jumlah Yang Diinputkan</label>
                                        <input type="number" class="form-control" name="jumlah" id="jumlah"
                                            value="{{ old('jumlah') }}" required min="0">
                                        @error('jumlah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jumlah Yang Harus Diinputkan</label>
                                        <input type="number" class="form-control bg-light" id="calculated_jumlah" readonly>
                                        <small class="form-text text-muted">
                                            Jumlah harus sama dengan total laki-laki dan perempuan
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @elseif($currentStep == 6)
                            <!-- Form Penggunaan Surat Suara -->
                            <div class="form-group">
                                <label>Tipe Pemilihan</label>
                                <input type="text" class="form-control" value="{{ $tipePemilihan->nama }}" readonly>
                                <input type="hidden" name="tipe_pemilihan" value="{{ $tipePemilihan->nama }}">
                            </div>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Jumlah surat suara yang digunakan</label>
                                        <input type="number"
                                            class="form-control @error('surat_suara_digunakan') is-invalid @enderror"
                                            name="surat_suara_digunakan" value="{{ old('surat_suara_digunakan') }}"
                                            required min="0" oninput="calculateSuratSuara()">
                                        @error('surat_suara_digunakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah surat suara yang dikembalikan</label>
                                        <input type="number"
                                            class="form-control @error('surat_suara_dikembalikan') is-invalid @enderror"
                                            name="surat_suara_dikembalikan" value="{{ old('surat_suara_dikembalikan') }}"
                                            required min="0" oninput="calculateSuratSuara()">
                                        @error('surat_suara_dikembalikan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah surat suara yang tidak digunakan</label>
                                        <input type="number"
                                            class="form-control @error('surat_suara_tidak_digunakan') is-invalid @enderror"
                                            name="surat_suara_tidak_digunakan"
                                            value="{{ old('surat_suara_tidak_digunakan') }}" required min="0"
                                            oninput="calculateSuratSuara()">
                                        @error('surat_suara_tidak_digunakan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah Surat Suara yang diterima</label>
                                        <input type="number"
                                            class="form-control @error('surat_suara_diterima') is-invalid @enderror"
                                            name="surat_suara_diterima" value="{{ old('surat_suara_diterima') }}"
                                            required min="0" oninput="calculateSuratSuara()">
                                        @error('surat_suara_diterima')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Penggunaan Surat Suara</label>
                                        <input type="number" class="form-control bg-light" id="total_penggunaan"
                                            readonly>
                                        <small class="form-text text-muted">
                                            Total penggunaan harus sama dengan jumlah surat suara yang diterima
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @elseif($currentStep == 8)
                            <!-- Form Input Suara Per Paslon -->
                            @foreach ($pasanganCalon as $paslon)
                                <div class="form-group">
                                    <label>{{ $paslon->nomor_urut }}. {{ $paslon->nama_pasangan }}</label>
                                    <input type="number"
                                        class="form-control @error('paslon_suara.' . $paslon->id) is-invalid @enderror"
                                        name="paslon_suara[{{ $paslon->id }}]"
                                        value="{{ old('paslon_suara.' . $paslon->id) }}" required min="0"
                                        oninput="calculateTotalSuaraPaslon()" min="0">
                                    @error('paslon_suara.' . $paslon->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <!-- Hidden input untuk total suara paslon -->
                            <input type="hidden" id="total_suara_paslon" value="0">

                            <div class="form-group">
                                <label>Total Suara Paslon</label>
                                <input type="number" class="form-control" id="display_total_suara_paslon" readonly>
                            </div>
                        @elseif($currentStep == 9)
                            <!-- Form Input Total Suara Sah dan Tidak Sah -->
                            <div class="form-group">
                                <label>Jumlah Seluruh Suara Sah</label>
                                <input type="number" name="jumlah_suara_sah"
                                    class="form-control @error('jumlah_suara_sah') is-invalid @enderror"
                                    value="{{ old('jumlah_suara_sah') }}" oninput="calculateTotalSuara()" required
                                    min="0">
                                @error('jumlah_suara_sah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jumlah Suara Tidak Sah</label>
                                <input type="number" name="jumlah_suara_tidak_sah"
                                    class="form-control @error('jumlah_suara_tidak_sah') is-invalid @enderror"
                                    value="{{ old('jumlah_suara_tidak_sah') }}" oninput="calculateTotalSuara()" required
                                    min="0">
                                @error('jumlah_suara_tidak_sah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jumlah Seluruh Suara Sah dan Tidak Sah</label>
                                <input type="number" name="total_suara_sah_dan_tidak_sah" class="form-control bg-light"
                                    id="total_suara_keseluruhan" readonly>
                            </div>

                            <div class="form-group">
                                <label>Total Suara Keseluruhan</label>
                                <input type="number" class="form-control bg-light" id="total_suara_final" readonly>
                            </div>
                        @elseif($currentStep == 10)
                            <!-- Form Uraian Hasil Pengawasan -->
                            <div class="form-group">
                                <label>Uraian Hasil Pengawasan</label>
                                <textarea name="uraian" class="form-control @error('uraian') is-invalid @enderror" rows="5" required>{{ old('uraian') }}</textarea>
                                @error('uraian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ $currentStep == 10 ? 'Simpan & Selesai' : 'Simpan & Lanjutkan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    @push('scripts')
        <script>
            function calculateSum() {
                const lakiLaki = parseInt(document.getElementById('laki_laki').value) || 0;
                const perempuan = parseInt(document.getElementById('perempuan').value) || 0;
                const jumlah = lakiLaki + perempuan;

                // Hanya kolom "Jumlah Yang Harus Diinputkan" yang diisi otomatis
                document.getElementById('calculated_jumlah').value = jumlah;
            }

            function calculateSuratSuara() {
                const suratSuaraDiterima = parseInt(document.querySelector('input[name="surat_suara_diterima"]').value) || 0;
                const suratSuaraDigunakan = parseInt(document.querySelector('input[name="surat_suara_digunakan"]').value) || 0;
                const suratSuaraDikembalikan = parseInt(document.querySelector('input[name="surat_suara_dikembalikan"]')
                    .value) || 0;
                const suratSuaraTidakDigunakan = parseInt(document.querySelector('input[name="surat_suara_tidak_digunakan"]')
                    .value) || 0;

                const totalPenggunaan = suratSuaraDigunakan + suratSuaraDikembalikan + suratSuaraTidakDigunakan;
                document.getElementById('total_penggunaan').value = totalPenggunaan;
            }

            function calculateTotalSuaraPaslon() {
                let total = 0;
                const inputs = document.querySelectorAll('input[name^="paslon_suara"]');
                inputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });

                document.getElementById('total_suara_paslon').value = total;
                document.getElementById('display_total_suara_paslon').value = total;

                const suaraSahInput = document.querySelector('input[name="jumlah_suara_sah"]');
                if (suaraSahInput) {
                    suaraSahInput.value = total;
                    calculateTotalSuara();
                }
            }

            function calculateTotalSuara() {
                const suaraSah = parseInt(document.querySelector('input[name="jumlah_suara_sah"]').value) || 0;
                const suaraTidakSah = parseInt(document.querySelector('input[name="jumlah_suara_tidak_sah"]').value) || 0;

                // Hitung total suara sah dan tidak sah
                const total = suaraSah + suaraTidakSah;
                document.getElementById('total_suara_keseluruhan').value = total;
                document.getElementById('total_suara_final').value = total;
            }



            function validateForm() {
                if ({{ $currentStep }} == 6) {
                    const suratSuaraDiterima = parseInt(document.querySelector('input[name="surat_suara_diterima"]').value) ||
                        0;
                    const totalPenggunaan = parseInt(document.getElementById('total_penggunaan').value) || 0;

                    if (suratSuaraDiterima !== totalPenggunaan) {
                        alert("Total penggunaan surat suara harus sama dengan jumlah surat suara yang diterima");
                        return false;
                    }
                } else if ({{ $currentStep }} == 8) {
                    const totalSuaraPaslon = parseInt(document.getElementById('total_suara_paslon').value) || 0;
                    if (totalSuaraPaslon < 0) {
                        alert("Total suara paslon tidak boleh kurang dari 0");
                        return false;
                    }
                } else if ({{ $currentStep }} == 9) {
                    const totalSuaraPaslon = parseInt(document.getElementById('total_suara_paslon').value) || 0;
                    const suaraSah = parseInt(document.querySelector('input[name="jumlah_suara_sah"]').value) || 0;

                    if (totalSuaraPaslon !== suaraSah) {
                        alert("Jumlah suara sah harus sama dengan total suara seluruh paslon");
                        return false;
                    }

                    const suaraTidakSah = parseInt(document.querySelector('input[name="jumlah_suara_tidak_sah"]').value) || 0;
                    const totalSuaraKeseluruhan = parseInt(document.getElementById('total_suara_keseluruhan').value) || 0;

                    if ((suaraSah + suaraTidakSah) !== totalSuaraKeseluruhan) {
                        alert("Total suara sah dan tidak sah harus sama dengan jumlah seluruh suara");
                        return false;
                    }
                } else if ({{ $currentStep }} == 10) {
                    const uraian = document.querySelector('textarea[name="uraian"]').value.trim();
                    if (uraian.length < 10) {
                        alert("Uraian hasil pengawasan terlalu pendek (minimal 10 karakter)");
                        return false;
                    }
                }
                return true;
            }

            document.addEventListener('DOMContentLoaded', function() {
                if ({{ $currentStep }} == 6) {
                    calculateSuratSuara();
                } else if ({{ $currentStep }} == 8) {
                    calculateTotalSuaraPaslon();
                } else if ({{ $currentStep }} == 9) {
                    calculateTotalSuara();
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                // Memastikan nilai awal dihitung
                calculateSum();
            });
        </script>
    @endpush


@endsection
