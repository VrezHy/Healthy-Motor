<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Diagnosa Kerusakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">🔧 Diagnosa Kerusakan Kendaraan</h1>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="flex justify-between mb-1">
            <span>Progres menjawab</span>
            <span id="progressPercent">0%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
        </div>
    </div>

    <!-- Slider Threshold -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <label class="block text-sm font-medium mb-2">🎯 Minimal persentase kecocokan: <span id="thresholdValue">60</span>%</label>
        <input type="range" id="thresholdSlider" min="0" max="100" value="60" class="w-full">
    </div>

    <!-- Form Pertanyaan -->
    <form id="diagnosaForm">
        <div class="space-y-3 mb-6">
            @foreach($gejala as $g)
            <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                <label class="font-medium">{{ $g->nama_gejala }}</label>
                <div class="space-x-3">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gejala[{{ $g->kode_gejala }}]" value="ya" class="gejala-radio">
                        <span class="ml-1">✅ Ya</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gejala[{{ $g->kode_gejala }}]" value="tidak" class="gejala-radio">
                        <span class="ml-1">❌ Tidak</span>
                    </label>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            🔍 Diagnosa Sekarang
        </button>
    </form>

    <!-- Hasil Diagnosa -->
    <div id="hasilDiagnosa" class="mt-8 hidden">
        <h2 class="text-xl font-bold mb-4">📊 Hasil Diagnosa</h2>
        <div id="hasilList" class="space-y-4"></div>
    </div>
</div>

<script>
    // Update progress bar
    const radios = document.querySelectorAll('.gejala-radio');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const thresholdSlider = document.getElementById('thresholdSlider');
    const thresholdValue = document.getElementById('thresholdValue');

    function updateProgress() {
        const totalGejala = {{ count($gejala) }};
        let terjawab = 0;
        document.querySelectorAll('.gejala-radio:checked').forEach(() => terjawab++);
        const percent = (terjawab / totalGejala) * 100;
        progressBar.style.width = percent + '%';
        progressPercent.innerText = Math.round(percent) + '%';
    }

    radios.forEach(radio => radio.addEventListener('change', updateProgress));
    thresholdSlider.addEventListener('input', function() {
        thresholdValue.innerText = this.value;
    });

    // Submit form
    document.getElementById('diagnosaForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const jawaban = {};
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('gejala[')) {
                const kode = key.match(/\[(.*?)\]/)[1];
                jawaban[kode] = value;
            }
        }

        const threshold = document.getElementById('thresholdSlider').value;

        const response = await fetch('{{ route("diagnosa.proses") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                gejala: jawaban,
                threshold: parseInt(threshold)
            })
        });

        const data = await response.json();

        if (data.success && data.hasil.length > 0) {
            const hasilDiv = document.getElementById('hasilList');
            hasilDiv.innerHTML = '';

            data.hasil.forEach((item, index) => {
                const warnaPersen = item.persentase >= 80 ? 'text-green-600' : (item.persentase >= 60 ? 'text-yellow-600' : 'text-orange-600');

                let solusiHtml = '';
                item.solusi.forEach(sol => {
                    solusiHtml += `
                        <div class="ml-4 mt-2 p-2 bg-gray-50 rounded">
                            <p class="font-semibold">🔧 ${sol.nama_solusi}</p>
                            <p class="text-sm text-gray-600">${sol.deskripsi}</p>
                        </div>
                    `;
                });

                hasilDiv.innerHTML += `
                    <div class="border rounded-lg p-4 ${index === 0 ? 'border-blue-400 bg-blue-50' : 'border-gray-200'}">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-lg">${item.kerusakan}</h3>
                            <span class="font-bold ${warnaPersen}">${item.persentase}%</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            ${item.gejala_cocok} dari ${item.total_gejala} gejala cocok
                        </div>
                        <div class="mt-2">
                            <strong>Solusi:</strong>
                            ${solusiHtml}
                        </div>
                    </div>
                `;
            });

            document.getElementById('hasilDiagnosa').classList.remove('hidden');
        } else {
            alert('Tidak ada kerusakan yang cocok dengan threshold minimal ' + data.threshold + '%');
        }
    });
</script>

</body>
</html>
