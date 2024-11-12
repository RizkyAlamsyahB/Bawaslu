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
                        <div class="progress-bar" role="progressbar" style="width: {{ ($currentStep / 12) * 100 }}%"
                            aria-valuenow="{{ $currentStep }}" aria-valuemin="0" aria-valuemax="12">
                            Step {{ $currentStep }} dari 12
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Progress: {{ number_format(($currentStep / 12) * 100, 0) }}%</span>
                        <span>Step {{ $currentStep }}/12</span>
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
                                Form Input Data Jumlah Pemilih DPTB
                            @break

                            @case(3)
                                Form Input Data Jumlah Pemilih DPK
                            @break

                            @case(4)
                                Form Input Data Jumlah Data Pemilih
                            @break

                            @case(5)
                                Form Input Data Pengguna Hak Pilih DPT
                            @break

                            @case(6)
                                Form Input Data Pengguna Hak Pilih DPTB
                            @break

                            @case(7)
                                Form Input Data Pengguna Hak Pilih DPK
                            @break

                            @case(8)
                                Form Input Data Jumlah Pengguna Hak Pilih
                            @break

                            @case(9)
                                Form Input Data Jumlah Pemilih Disabilitas
                            @break

                            @case(10)
                                Form Input Data Pengguna Hak Pilih Disabilitas
                            @break

                            @case(11)
                                Form Input Data Penggunaan Surat Suara
                            @break

                            @case(12)
                                Form Input Data Suara Sah
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

                        @if ($currentStep >= 1 && $currentStep <= 10)
                            <!-- Form untuk step 1-10 (form standar laki-laki, perempuan, jumlah) -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Laki-laki</label>
                                        <input type="number" class="form-control @error('laki_laki') is-invalid @enderror"
                                            name="laki_laki" value="{{ old('laki_laki') }}" required min="0"
                                            oninput="calculateSum()">
                                        @error('laki_laki')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Perempuan</label>
                                        <input type="number" class="form-control @error('perempuan') is-invalid @enderror"
                                            name="perempuan" value="{{ old('perempuan') }}" required min="0"
                                            oninput="calculateSum()">
                                        @error('perempuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jumlah Yang Diinputkan</label>
                                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                            name="jumlah" value="{{ old('jumlah') }}" required min="0">
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
                        @elseif($currentStep == 11)
                            <!-- Form Penggunaan Surat Suara -->
                            <div class="form-group">
                                <label>Tipe Pemilihan</label>
                                <input type="text" class="form-control" value="{{ $tipePemilihan->nama }}" readonly>
                                <input type="hidden" name="tipe_pemilihan" value="{{ $tipePemilihan->nama }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Surat Suara Dikembalikan</label>
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
                                        <label>Surat Suara Tidak Digunakan</label>
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
                                        <label>Surat Suara Digunakan</label>
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
                                        <label>Jumlah Surat Suara yang Diterima</label>
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
                        @elseif($currentStep == 12)
                            <!-- Form Data Suara Sah -->
                            <div class="form-group">
                                <label>Pasangan Calon</label>
                                <select name="pasangan_calon_id"
                                    class="form-control @error('pasangan_calon_id') is-invalid @enderror" required>
                                    <option value="">Pilih Pasangan Calon</option>
                                    @foreach ($pasanganCalon as $pc)
                                        <option value="{{ $pc->id }}"
                                            {{ old('pasangan_calon_id') == $pc->id ? 'selected' : '' }}>
                                            {{ $pc->nomor_urut }} - {{ $pc->nama_pasangan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pasangan_calon_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah Suara Sah</label>
                                        <input type="number"
                                            class="form-control @error('jumlah_suara_sah') is-invalid @enderror"
                                            name="jumlah_suara_sah" value="{{ old('jumlah_suara_sah') }}" required
                                            min="0" oninput="calculateTotal()">
                                        @error('jumlah_suara_sah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jumlah Suara Tidak Sah</label>
                                        <input type="number"
                                            class="form-control @error('jumlah_suara_tidak_sah') is-invalid @enderror"
                                            name="jumlah_suara_tidak_sah" value="{{ old('jumlah_suara_tidak_sah') }}"
                                            required min="0" oninput="calculateTotal()">
                                        @error('jumlah_suara_tidak_sah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Total Suara Sah dan Tidak Sah</label>
                                <input type="number" class="form-control bg-light" id="total_suara"
                                    name="total_suara_sah_dan_tidak_sah" readonly>
                                <small class="form-text text-muted">
                                    Total akan dihitung otomatis dari jumlah suara sah dan tidak sah
                                </small>
                            </div>
                        @endif

                        <div class="form-group text-right">

                            <button type="submit" class="btn btn-primary">
                                {{ $currentStep == 12 ? 'Simpan & Selesai' : 'Simpan & Lanjutkan' }}
                            </button>
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
            if ({{ $currentStep }} >= 1 && {{ $currentStep }} <= 10) {
                const lakiLaki = parseInt(document.querySelector('input[name="laki_laki"]').value) || 0;
                const perempuan = parseInt(document.querySelector('input[name="perempuan"]').value) || 0;
                document.getElementById('calculated_jumlah').value = lakiLaki + perempuan;
            }
        }

        function calculateSuratSuara() {
            if ({{ $currentStep }} == 11) {
                const dikembalikan = parseInt(document.querySelector('input[name="surat_suara_dikembalikan"]').value) || 0;
                const tidakDigunakan = parseInt(document.querySelector('input[name="surat_suara_tidak_digunakan"]')
                    .value) || 0;
                const digunakan = parseInt(document.querySelector('input[name="surat_suara_digunakan"]').value) || 0;
                const diterima = parseInt(document.querySelector('input[name="surat_suara_diterima"]').value) || 0;

                const totalPenggunaan = dikembalikan + tidakDigunakan + digunakan;
                document.getElementById('total_penggunaan').value = totalPenggunaan;

                const totalElement = document.getElementById('total_penggunaan');
                if (diterima > 0 && totalPenggunaan !== diterima) {
                    totalElement.classList.add('is-invalid');
                    totalElement.parentElement.querySelector('.form-text').classList.add('text-danger');
                } else {
                    totalElement.classList.remove('is-invalid');
                    totalElement.parentElement.querySelector('.form-text').classList.remove('text-danger');
                }
            }
        }

        function calculateTotal() {
            if ({{ $currentStep }} == 12) {
                const suaraSah = parseInt(document.querySelector('input[name="jumlah_suara_sah"]').value) || 0;
                const suaraTidakSah = parseInt(document.querySelector('input[name="jumlah_suara_tidak_sah"]').value) || 0;
                document.getElementById('total_suara').value = suaraSah + suaraTidakSah;
            }
        }

        function validateForm() {
            if ({{ $currentStep }} >= 1 && {{ $currentStep }} <= 10) {
                const lakiLaki = document.querySelector('input[name="laki_laki"]');
                const perempuan = document.querySelector('input[name="perempuan"]');
                const jumlah = document.querySelector('input[name="jumlah"]');
                const calculatedJumlah = document.getElementById('calculated_jumlah');

                if (!lakiLaki.value) {
                    alert('Kolom laki-laki harus diisi');
                    lakiLaki.focus();
                    return false;
                }

                if (!perempuan.value) {
                    alert('Kolom perempuan harus diisi');
                    perempuan.focus();
                    return false;
                }

                if (!jumlah.value) {
                    alert('Kolom jumlah harus diisi');
                    jumlah.focus();
                    return false;
                }

                if (parseInt(jumlah.value) !== parseInt(calculatedJumlah.value)) {
                    alert("Jumlah yang diinputkan harus sama dengan jumlah yang dihitung (Laki-laki + Perempuan)");
                    return false;
                }
            } else if ({{ $currentStep }} == 11) {
                const diterima = parseInt(document.querySelector('input[name="surat_suara_diterima"]').value);
                const totalPenggunaan = parseInt(document.getElementById('total_penggunaan').value);

                if (totalPenggunaan !== diterima) {
                    alert("Total penggunaan surat suara harus sama dengan jumlah surat suara yang diterima");
                    return false;
                }
            }
            return true;
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', function() {
            if ({{ $currentStep }} >= 1 && {{ $currentStep }} <= 10) {
                calculateSum();
            } else if ({{ $currentStep }} == 11) {
                calculateSuratSuara();
            } else if ({{ $currentStep }} == 12) {
                calculateTotal();
            }
        });
    </script>
@endpush
