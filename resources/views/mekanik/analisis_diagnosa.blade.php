<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/dashboard_mekanik.css') }}">
    <title>Diagnosa Mekanik</title>
</head>
<body>

<div class="header">
    DM5S
</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile">
            <img src="{{ asset('assets/images/mekanik.jpg') }}" alt="Foto Profil">
            <h3>{{ auth()->user()->name }}</h3>
            <small>{{ auth()->user()->username }}</small>
        </div>

        <div class="menu">
            <a href="{{ route('mekanik.diagnosa') }}" class="active">Analisis Diagnosa</a>
            <a href="{{ route('mekanik.riwayat') }}">Log Riwayat</a>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="title">Dashboard Mekanik</div>

        <div class="wizard-center">
            <form action="{{ route('mekanik.diagnosa.proses') }}" method="POST" class="wizard-card" id="diagnosaForm">
                @csrf

                @if($gejalas->isEmpty())
                    <div class="wizard-empty">
                        <h2>Data gejala belum tersedia</h2>
                        <p>Tambahkan data gejala melalui dashboard admin terlebih dahulu.</p>
                    </div>
                @else
                    <div class="wizard-progress">
                        <span id="stepCounter">1 / {{ $gejalas->count() }}</span>
                    </div>

                    @foreach($gejalas as $gejala)
                        @php
                            $nilai = isset($jawaban) ? ($jawaban[$gejala->id] ?? null) : null;
                        @endphp

                        <div class="wizard-step {{ $loop->first ? 'active' : '' }}" data-step="{{ $loop->index }}">
                            <span class="question-code">{{ $gejala->kode_gejala }}</span>
                            <h2>Apakah gejala ini terjadi?</h2>
                            <p>{{ $gejala->nama_gejala }}</p>

                            <div class="wizard-options">
                                <label>
                                    <input type="radio" name="jawaban[{{ $gejala->id }}]" value="ya" {{ $nilai === 'ya' ? 'checked' : '' }}>
                                    <span>Ya</span>
                                </label>
                                <label>
                                    <input type="radio" name="jawaban[{{ $gejala->id }}]" value="tidak" {{ $nilai === 'tidak' ? 'checked' : '' }}>
                                    <span>Tidak</span>
                                </label>
                            </div>

                            <small class="wizard-error">Pilih jawaban terlebih dahulu</small>
                        </div>
                    @endforeach

                    <div class="wizard-actions">
                        <button type="button" class="wizard-btn previous" id="prevBtn">Previous</button>
                        <button type="button" class="wizard-btn next" id="nextBtn">Next</button>
                        <button type="submit" class="wizard-btn submit" id="submitBtn">Proses</button>
                    </div>
                @endif
            </form>

            <div class="diagnosa-result">
                <div class="result-heading">
                    <h2>Hasil Diagnosa</h2>
                    @isset($selectedGejalas)
                        <span>{{ $selectedGejalas->count() }} gejala dipilih</span>
                    @else
                        <span>Menunggu proses</span>
                    @endisset
                </div>

                @if(!isset($hasil))
                    <div class="result-placeholder">
                        <h3>Belum ada hasil diagnosa</h3>
                        <p>Jawab pertanyaan gejala di sebelah kiri, lalu klik tombol Proses.</p>
                    </div>
                @elseif($selectedGejalas->isEmpty())
                    <p class="result-message">Belum ada gejala yang dijawab "Ya", jadi hasil diagnosa belum bisa ditentukan.</p>
                @elseif($hasil->isEmpty())
                    <p class="result-message">Gejala yang dipilih belum cocok dengan data kerusakan.</p>
                @else
                    @foreach($hasil as $item)
                        <div class="result-item">
                            <div class="result-title">
                                <strong>{{ $item->kerusakan->nama_kerusakan }}</strong>
                                <span>{{ $item->persentase }}%</span>
                            </div>
                            <div class="result-bar">
                                <span style="width: {{ $item->persentase }}%"></span>
                            </div>
                            <p>{{ $item->jumlahCocok }} dari {{ $item->totalGejala }} gejala cocok.</p>

                            @if($item->kerusakan->solusies->isNotEmpty())
                                <div class="solution-box">
                                    <strong>Solusi</strong>
                                    @foreach($item->kerusakan->solusies as $solusi)
                                        <p>{{ $solusi->nama_solusi }}{{ $solusi->deskripsi ? ' - ' . $solusi->deskripsi : '' }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <form action="{{ route('mekanik.diagnosa.simpan') }}" method="POST" class="save-diagnosa-form">
                        @csrf
                        @foreach($selectedGejalas as $gejala)
                            <input type="hidden" name="gejala_ids[]" value="{{ $gejala->id }}">
                        @endforeach

                        <button type="submit" class="btn-save-riwayat">Simpan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</div>

<div class="logout-modal" id="logoutModal">
    <div class="logout-modal-box">
        <h2>Yakin Keluar Dari Aplikasi?</h2>
        <div class="logout-modal-actions">
            <button type="button" class="modal-btn cancel" id="cancelLogout">batal</button>
            <button type="button" class="modal-btn confirm" id="confirmLogout">keluar</button>
        </div>
    </div>
</div>

<script>
    let pendingLogoutForm = null;
    const logoutModal = document.getElementById('logoutModal');
    const steps = Array.from(document.querySelectorAll('.wizard-step'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const stepCounter = document.getElementById('stepCounter');
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, stepIndex) => {
            step.classList.toggle('active', stepIndex === index);
            step.classList.remove('has-error');
        });

        if (!steps.length) return;

        prevBtn.disabled = index === 0;
        nextBtn.style.display = index === steps.length - 1 ? 'none' : 'inline-flex';
        submitBtn.style.display = index === steps.length - 1 ? 'inline-flex' : 'none';
        stepCounter.textContent = `${index + 1} / ${steps.length}`;
    }

    function hasAnswer(step) {
        return Boolean(step.querySelector('input[type="radio"]:checked'));
    }

    nextBtn?.addEventListener('click', () => {
        const activeStep = steps[currentStep];

        if (!hasAnswer(activeStep)) {
            activeStep.classList.add('has-error');
            return;
        }

        currentStep = Math.min(currentStep + 1, steps.length - 1);
        showStep(currentStep);
    });

    prevBtn?.addEventListener('click', () => {
        currentStep = Math.max(currentStep - 1, 0);
        showStep(currentStep);
    });

    document.getElementById('diagnosaForm')?.addEventListener('submit', (event) => {
        const activeStep = steps[currentStep];

        if (activeStep && !hasAnswer(activeStep)) {
            event.preventDefault();
            activeStep.classList.add('has-error');
        }
    });

    showStep(currentStep);

    document.querySelectorAll('.logout-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingLogoutForm = form;
            logoutModal.classList.add('active');
        });
    });

    document.getElementById('cancelLogout').addEventListener('click', () => {
        pendingLogoutForm = null;
        logoutModal.classList.remove('active');
    });

    document.getElementById('confirmLogout').addEventListener('click', () => {
        if (pendingLogoutForm) {
            pendingLogoutForm.submit();
        }
    });
</script>

</body>
</html>
