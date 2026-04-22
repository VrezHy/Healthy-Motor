<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DM5S - Dashboard Mekanik</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --bg-sidebar:#7B84B8;
  --bg-content:#9099BF;
  --bg-header:#6168A0;
  --btn-active:#4A52A3;
  --btn-hover:rgba(255,255,255,0.15);
  --text-white:#ffffff;
  --text-muted:rgba(255,255,255,0.75);
  --avatar-bg:#C8CDDF;
  --logout-bg:rgba(255,255,255,0.2);
  --logout-border:rgba(255,255,255,0.35);
  --card-bg:#7278B0;
  --card-inner:#8389BF;
  --accent:#4A52A3;
  --accent-hover:#3a42a0;
  --error:#f87171;
  --success:#34d399;
}
body{font-family:'Plus Jakarta Sans',sans-serif;height:100vh;display:flex;flex-direction:column;overflow:hidden;background:var(--bg-sidebar)}

/* HEADER */
.header{background:var(--bg-header);height:52px;display:flex;align-items:center;padding:0 24px;flex-shrink:0;border-bottom:1px solid rgba(0,0,0,.12)}
.header-logo{font-family:'Rajdhani',sans-serif;font-size:26px;font-weight:700;font-style:italic;color:#fff;letter-spacing:1px}

/* LAYOUT */
.body-layout{display:flex;flex:1;overflow:hidden}

/* SIDEBAR */
.sidebar{width:200px;background:var(--bg-sidebar);display:flex;flex-direction:column;align-items:center;padding:30px 16px 24px;border-right:1px solid rgba(0,0,0,.1);flex-shrink:0}
.avatar-wrap{width:90px;height:90px;border-radius:50%;background:var(--avatar-bg);margin-bottom:14px;border:3px solid rgba(255,255,255,.3);overflow:hidden;display:flex;align-items:center;justify-content:center}
.avatar-wrap svg{width:52px;height:52px;opacity:.5}
.user-name{font-size:15px;font-weight:600;color:#fff;text-align:center;margin-bottom:2px}
.user-username{font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:28px}
.nav-menu{width:100%;display:flex;flex-direction:column;gap:4px}
.nav-item{width:100%;padding:9px 14px;border-radius:20px;font-size:13px;font-weight:500;color:var(--text-muted);cursor:pointer;transition:background .18s,color .18s;text-align:left;background:none;border:none;font-family:inherit;letter-spacing:.01em}
.nav-item:hover{background:var(--btn-hover);color:#fff}
.nav-item.active{background:var(--btn-active);color:#fff;font-weight:600}
.sidebar-spacer{flex:1}
.logout-btn{width:100%;padding:9px 14px;border-radius:20px;font-size:13px;font-weight:600;color:#fff;cursor:pointer;background:var(--logout-bg);border:1px solid var(--logout-border);font-family:inherit;text-align:center;transition:background .18s;letter-spacing:.02em}
.logout-btn:hover{background:rgba(255,255,255,.28)}

/* MAIN */
.main-content{flex:1;background:var(--bg-content);display:flex;flex-direction:column;padding:32px 36px;overflow-y:auto}
.page-title{font-size:22px;font-weight:600;color:#fff;letter-spacing:.01em;margin-bottom:0}
.content-center{flex:1;display:flex;align-items:center;justify-content:center}

/* START BUTTON */
.diagnosa-btn{padding:14px 48px;border-radius:28px;background:var(--accent);color:#fff;font-size:13px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;border:none;cursor:pointer;font-family:inherit;transition:background .18s,transform .12s;line-height:1.4;text-align:center}
.diagnosa-btn:hover{background:var(--accent-hover);transform:translateY(-1px)}

/* DIAGNOSTIC CARD */
.diag-card{background:var(--card-bg);border-radius:20px;width:100%;max-width:420px;padding:36px 32px 28px;display:none;flex-direction:column;min-height:380px;position:relative}
.diag-card.visible{display:flex}

/* PROGRESS */
.progress-wrap{margin-bottom:20px}
.progress-bar-bg{height:5px;background:rgba(255,255,255,.2);border-radius:99px;overflow:hidden}
.progress-bar-fill{height:5px;background:rgba(255,255,255,.8);border-radius:99px;transition:width .4s ease}
.progress-label{font-size:11px;color:rgba(255,255,255,.65);margin-top:6px;text-align:right}

/* QUESTION */
.q-title{font-size:22px;font-weight:700;color:#fff;margin-bottom:20px;line-height:1.3}
.q-subtitle{font-size:13px;color:rgba(255,255,255,.7);margin-bottom:20px;margin-top:-12px}

/* RADIO OPTIONS */
.options-list{display:flex;flex-direction:column;gap:10px;margin-bottom:8px}
.option-item{display:flex;align-items:center;gap:12px;cursor:pointer;padding:10px 14px;border-radius:12px;transition:background .15s;background:rgba(255,255,255,.08)}
.option-item:hover{background:rgba(255,255,255,.16)}
.option-item input[type=radio]{display:none}
.radio-circle{width:20px;height:20px;border-radius:50%;border:2px solid rgba(255,255,255,.6);flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:border-color .15s,background .15s}
.option-item.selected .radio-circle{border-color:#fff;background:#fff}
.radio-dot{width:9px;height:9px;border-radius:50%;background:var(--accent);display:none}
.option-item.selected .radio-dot{display:block}
.option-label{font-size:14px;color:#fff;font-weight:500}

/* GRID OPTIONS (for brands) */
.options-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:8px}
.option-grid-item{display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 12px;border-radius:12px;transition:background .15s;background:rgba(255,255,255,.08)}
.option-grid-item:hover{background:rgba(255,255,255,.16)}
.option-grid-item.selected{background:rgba(255,255,255,.18)}
.option-grid-item input[type=radio]{display:none}

/* ERROR */
.error-msg{font-size:12px;color:var(--error);margin-top:6px;margin-bottom:4px;font-weight:500;display:none;align-items:center;gap:5px}
.error-msg.show{display:flex}
.error-icon{font-size:13px}

/* FOOTER NAV */
.card-footer{display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:20px}
.btn-back{padding:9px 22px;border-radius:20px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:background .15s;display:none}
.btn-back:hover{background:rgba(255,255,255,.25)}
.btn-back.visible{display:block}
.btn-next{padding:10px 28px;border-radius:20px;background:rgba(255,255,255,.25);border:1px solid rgba(255,255,255,.4);color:#fff;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s,transform .12s;letter-spacing:.05em}
.btn-next:hover{background:rgba(255,255,255,.35);transform:translateY(-1px)}
.btn-next:active{transform:translateY(0)}

/* RESULT CARD */
.result-section{display:flex;flex-direction:column;gap:0}
.result-header{font-size:18px;font-weight:700;color:#fff;margin-bottom:16px}
.result-item{background:rgba(255,255,255,.1);border-radius:12px;padding:14px 16px;margin-bottom:10px}
.result-item-title{font-size:13px;color:rgba(255,255,255,.7);font-weight:500;margin-bottom:4px}
.result-item-val{font-size:14px;color:#fff;font-weight:600}
.diagnosis-box{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:14px;padding:16px;margin-bottom:10px}
.diagnosis-label{font-size:11px;font-weight:700;letter-spacing:.1em;color:rgba(255,255,255,.6);text-transform:uppercase;margin-bottom:8px}
.diagnosis-text{font-size:14px;color:#fff;line-height:1.6}
.severity-badge{display:inline-block;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:12px}
.severity-low{background:rgba(52,211,153,.25);color:#a7f3d0;border:1px solid rgba(52,211,153,.4)}
.severity-med{background:rgba(251,191,36,.25);color:#fde68a;border:1px solid rgba(251,191,36,.4)}
.severity-high{background:rgba(248,113,113,.25);color:#fca5a5;border:1px solid rgba(248,113,113,.4)}
.btn-restart{padding:10px 22px;border-radius:20px;background:var(--accent);border:none;color:#fff;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s;width:100%;margin-top:6px}
.btn-restart:hover{background:var(--accent-hover)}

/* STEP indicator dots */
.step-dots{display:flex;gap:6px;margin-bottom:18px}
.step-dot{width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.25);transition:background .3s,transform .2s}
.step-dot.active{background:#fff;transform:scale(1.2)}
.step-dot.done{background:rgba(255,255,255,.55)}

/* Scrollable options */
.options-scroll{max-height:220px;overflow-y:auto;padding-right:4px}
.options-scroll::-webkit-scrollbar{width:4px}
.options-scroll::-webkit-scrollbar-track{background:rgba(255,255,255,.05)}
.options-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.2);border-radius:99px}
</style>
</head>
<body>

<header class="header">
  <span class="header-logo">DM5S</span>
</header>

<div class="body-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="avatar-wrap">
      <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" fill="white"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" fill="white"/></svg>
    </div>
    <div class="user-name">Nama Akun</div>
    <div class="user-username">Nama Username</div>
    <nav class="nav-menu">
      <button class="nav-item active" onclick="setActive(this)">Analisis Diagnosa</button>
      <button class="nav-item" onclick="setActive(this)">Log Riwayat</button>
    </nav>
    <div class="sidebar-spacer"></div>
    <button class="logout-btn">Logout</button>
  </aside>

  <!-- MAIN -->
  <main class="main-content">
    <h1 class="page-title">Dashboard Mekanik</h1>

    <div class="content-center">

      <!-- START SCREEN -->
      <div id="startScreen">
        <button class="diagnosa-btn" onclick="startDiagnosa()">MULAI<br>DIAGNOSA</button>
      </div>

      <!-- DIAGNOSTIC CARD -->
      <div class="diag-card" id="diagCard">

        <!-- Progress -->
        <div class="progress-wrap">
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
          <div class="progress-label" id="progressLabel">Langkah 1 dari 9</div>
        </div>

        <!-- Step dots -->
        <div class="step-dots" id="stepDots"></div>

        <!-- Question content injected here -->
        <div id="questionContent"></div>

        <!-- Error message -->
        <div class="error-msg" id="errorMsg">
          <span class="error-icon">⚠</span>
          <span id="errorText">Silakan pilih salah satu jawaban sebelum melanjutkan.</span>
        </div>

        <!-- Footer nav -->
        <div class="card-footer">
          <button class="btn-back" id="btnBack" onclick="prevStep()">← Kembali</button>
          <button class="btn-next" id="btnNext" onclick="nextStep()">Next →</button>
        </div>
      </div>

    </div>
  </main>
</div>

<script>
const TOTAL_STEPS = 9;
let currentStep = 0;
let answers = {};

const questions = [
  {
    id: 'merek',
    title: 'Pilih Merek\nKendaraan',
    layout: 'grid',
    options: [
      {val:'yamaha', label:'Yamaha'},
      {val:'honda', label:'Honda'},
      {val:'suzuki', label:'Suzuki'},
      {val:'kawasaki', label:'Kawasaki'},
      {val:'vespa', label:'Vespa'},
      {val:'tvs', label:'TVS'},
      {val:'bajaj', label:'Bajaj'},
      {val:'lainnya', label:'Lainnya'},
    ]
  },
  {
    id: 'jenis',
    title: 'Jenis Motor',
    options: [
      {val:'bebek', label:'Motor Bebek (Supra, Vega, dll)'},
      {val:'matic', label:'Motor Matic (Vario, Mio, dll)'},
      {val:'sport', label:'Motor Sport (CBR, R15, dll)'},
      {val:'trail', label:'Motor Trail / Off-road'},
    ]
  },
  {
    id: 'keluhan',
    title: 'Keluhan Utama',
    subtitle: 'Pilih keluhan yang paling dominan',
    options: [
      {val:'mesin_mati', label:'Mesin tidak mau hidup'},
      {val:'mati_mendadak', label:'Mesin mati tiba-tiba saat jalan'},
      {val:'brebet', label:'Motor brebet / tersendat'},
      {val:'suara_aneh', label:'Suara mesin tidak normal'},
      {val:'boros_bbm', label:'Boros bahan bakar'},
      {val:'asap_knalpot', label:'Asap knalpot berlebih'},
      {val:'rem_blong', label:'Rem tidak pakem / blong'},
      {val:'kelistrikan', label:'Masalah kelistrikan (lampu, aki)'},
      {val:'getaran', label:'Getaran berlebih'},
      {val:'overheat', label:'Mesin cepat panas'},
    ],
    scroll: true
  },
  {
    id: 'kapan',
    title: 'Kapan Keluhan\nMuncul?',
    options: [
      {val:'start', label:'Saat pertama dinyalakan'},
      {val:'jalan', label:'Saat sedang berkendara'},
      {val:'berhenti', label:'Saat berhenti / idle'},
      {val:'selalu', label:'Sepanjang waktu'},
    ]
  },
  {
    id: 'durasi',
    title: 'Sudah Berapa\nLama Keluhan Ini?',
    options: [
      {val:'kurang1hari', label:'Kurang dari 1 hari'},
      {val:'1_7hari', label:'1 - 7 hari'},
      {val:'1_4minggu', label:'1 - 4 minggu'},
      {val:'lebih1bulan', label:'Lebih dari 1 bulan'},
    ]
  },
  {
    id: 'suara',
    title: 'Apakah Ada\nSuara Aneh?',
    options: [
      {val:'ketukan', label:'Suara ketukan (tok-tok)'},
      {val:'decit', label:'Suara berdecit / gesekan'},
      {val:'gemuruh', label:'Suara gemuruh / dengung'},
      {val:'letupan', label:'Suara letupan dari knalpot'},
      {val:'tidak_ada', label:'Tidak ada suara aneh'},
    ]
  },
  {
    id: 'oli',
    title: 'Kondisi Oli\nMesin?',
    options: [
      {val:'baru', label:'Baru diganti (< 1 bulan)'},
      {val:'waktunya', label:'Sudah waktunya ganti'},
      {val:'berkurang', label:'Oli berkurang drastis'},
      {val:'hitam', label:'Oli sangat hitam & kental'},
      {val:'belum_cek', label:'Belum pernah dicek'},
    ]
  },
  {
    id: 'servis',
    title: 'Kapan Terakhir\nServis?',
    options: [
      {val:'lt1bulan', label:'Kurang dari 1 bulan lalu'},
      {val:'1_3bulan', label:'1 - 3 bulan lalu'},
      {val:'3_6bulan', label:'3 - 6 bulan lalu'},
      {val:'gt6bulan', label:'Lebih dari 6 bulan'},
      {val:'belum_pernah', label:'Belum pernah servis'},
    ]
  },
  {
    id: 'bbm',
    title: 'Kondisi Bahan\nBakar?',
    options: [
      {val:'penuh', label:'Tangki penuh / baru isi'},
      {val:'hampir_habis', label:'Hampir habis'},
      {val:'pertamax', label:'Menggunakan Pertamax / Ron 92+'},
      {val:'pertalite', label:'Menggunakan Pertalite / Premium'},
      {val:'campur', label:'Kadang campur jenis BBM'},
    ]
  }
];

function startDiagnosa(){
  document.getElementById('startScreen').style.display='none';
  const card = document.getElementById('diagCard');
  card.classList.add('visible');
  currentStep = 0;
  answers = {};
  buildStepDots();
  renderStep();
}

function buildStepDots(){
  const wrap = document.getElementById('stepDots');
  wrap.innerHTML = '';
  for(let i=0;i<TOTAL_STEPS;i++){
    const d = document.createElement('div');
    d.className='step-dot';
    d.id='dot-'+i;
    wrap.appendChild(d);
  }
}

function updateDots(){
  for(let i=0;i<TOTAL_STEPS;i++){
    const d = document.getElementById('dot-'+i);
    if(i < currentStep) d.className='step-dot done';
    else if(i===currentStep) d.className='step-dot active';
    else d.className='step-dot';
  }
}

function renderStep(){
  hideError();
  updateDots();
  const pct = Math.round((currentStep/TOTAL_STEPS)*100);
  document.getElementById('progressBar').style.width=pct+'%';
  document.getElementById('progressLabel').textContent='Langkah '+(currentStep+1)+' dari '+TOTAL_STEPS;

  const back = document.getElementById('btnBack');
  const next = document.getElementById('btnNext');
  back.className = currentStep>0?'btn-back visible':'btn-back';

  const q = questions[currentStep];
  const content = document.getElementById('questionContent');
  const saved = answers[q.id];

  let titleHtml = q.title.replace('\n','<br>');
  let html = `<div class="q-title">${titleHtml}</div>`;
  if(q.subtitle) html+=`<div class="q-subtitle">${q.subtitle}</div>`;

  const listClass = q.scroll?'options-scroll':'';
  if(q.layout==='grid'){
    html+=`<div class="options-grid ${listClass}" id="optWrap">`;
    q.options.forEach(o=>{
      const sel = saved===o.val?'selected':'';
      html+=`<label class="option-grid-item ${sel}" onclick="selectOption('${q.id}','${o.val}',this,'grid')">
        <input type="radio" name="${q.id}" value="${o.val}" ${saved===o.val?'checked':''}>
        <div class="radio-circle"><div class="radio-dot"></div></div>
        <span class="option-label">${o.label}</span>
      </label>`;
    });
    html+=`</div>`;
  } else {
    html+=`<div class="options-list ${listClass}" id="optWrap">`;
    q.options.forEach(o=>{
      const sel = saved===o.val?'selected':'';
      html+=`<label class="option-item ${sel}" onclick="selectOption('${q.id}','${o.val}',this,'list')">
        <input type="radio" name="${q.id}" value="${o.val}" ${saved===o.val?'checked':''}>
        <div class="radio-circle"><div class="radio-dot"></div></div>
        <span class="option-label">${o.label}</span>
      </label>`;
    });
    html+=`</div>`;
  }

  content.innerHTML = html;
  next.textContent = currentStep===TOTAL_STEPS-1?'Lihat Hasil ✓':'Next →';
}

function selectOption(qid, val, el, layout){
  answers[qid]=val;
  hideError();
  const parent = el.parentElement;
  const cls = layout==='grid'?'.option-grid-item':'.option-item';
  parent.querySelectorAll(cls).forEach(i=>i.classList.remove('selected'));
  el.classList.add('selected');
}

function nextStep(){
  const q = questions[currentStep];
  if(!answers[q.id]){
    showError('Silakan pilih salah satu jawaban sebelum melanjutkan.');
    return;
  }
  if(currentStep<TOTAL_STEPS-1){
    currentStep++;
    renderStep();
  } else {
    showResult();
  }
}

function prevStep(){
  if(currentStep>0){currentStep--;renderStep();}
}

function showError(msg){
  const e=document.getElementById('errorMsg');
  document.getElementById('errorText').textContent=msg;
  e.classList.add('show');
  const card=document.getElementById('diagCard');
  card.style.animation='none';card.offsetHeight;
  card.style.animation='shake .35s ease';
}

function hideError(){
  document.getElementById('errorMsg').classList.remove('show');
}

function getDiagnosis(){
  const k=answers.keluhan;
  const oli=answers.oli;
  const suara=answers.suara;
  const servis=answers.servis;

  let severity='low', badge='Ringan', diagnoses=[], recs=[];

  if(k==='mesin_mati'){
    severity='high'; badge='Perhatian Tinggi';
    if(answers.bbm==='hampir_habis'){diagnoses.push('Kemungkinan besar kehabisan bahan bakar.');recs.push('Isi bahan bakar segera.');}
    else if(oli==='belum_cek'||oli==='berkurang'){diagnoses.push('Oli mesin kritis atau habis menyebabkan mesin tidak mau start.');recs.push('Cek dan ganti oli mesin segera.');}
    else{diagnoses.push('Kemungkinan busi lemah, karburator/injektor kotor, atau aki melemah.');recs.push('Cek busi, aki, dan sistem bahan bakar.');}
  } else if(k==='mati_mendadak'){
    severity='high'; badge='Perhatian Tinggi';
    diagnoses.push('Mesin mati mendadak sering disebabkan oleh overheating, masalah sistem pengapian, atau bahan bakar tersumbat.');
    recs.push('Periksa suhu mesin, kondisi busi, dan filter bahan bakar.');
    if(suara==='ketukan') recs.push('Suara ketukan mengindikasikan kerusakan pada piston atau klep — segera ke bengkel.');
  } else if(k==='brebet'){
    severity='med'; badge='Perlu Perhatian';
    diagnoses.push('Motor brebet umumnya disebabkan oleh busi kotor, karburator/injektor kotor, atau campuran bahan bakar tidak tepat.');
    recs.push('Bersihkan karburator/throttle body, ganti busi jika sudah lama.');
    if(servis==='gt6bulan'||servis==='belum_pernah') recs.push('Jadwalkan servis lengkap segera karena sudah terlalu lama.');
  } else if(k==='suara_aneh'){
    if(suara==='ketukan'){severity='high';badge='Perhatian Tinggi';diagnoses.push('Suara ketukan (knocking) mengindikasikan masalah serius pada piston, klep, atau bearing mesin.');recs.push('Hentikan penggunaan dan bawa ke bengkel segera.');}
    else if(suara==='decit'){severity='med';badge='Perlu Perhatian';diagnoses.push('Suara berdecit biasanya dari rantai kendur, kampas rem tipis, atau belt CVT aus.');recs.push('Periksa rantai, rem, dan belt CVT.');}
    else{severity='med';badge='Perlu Perhatian';diagnoses.push('Suara gemuruh atau letupan bisa dari knalpot bocor atau bearing roda bermasalah.');recs.push('Periksa knalpot dan bearing roda.');}
  } else if(k==='boros_bbm'){
    severity='low'; badge='Ringan';
    diagnoses.push('Konsumsi BBM berlebih sering disebabkan oleh filter udara kotor, busi lemah, atau setelan karburator yang kacau.');
    recs.push('Ganti filter udara, cek busi, kalibrasi karburator/injektor.');
  } else if(k==='asap_knalpot'){
    severity='med'; badge='Perlu Perhatian';
    diagnoses.push('Asap putih/biru = oli terbakar (ring piston aus). Asap hitam = campuran BBM terlalu kaya.');
    recs.push('Jika asap biru/putih, segera cek ring piston dan seal klep.');
  } else if(k==='rem_blong'){
    severity='high'; badge='BAHAYA - Segera Tangani';
    diagnoses.push('Rem tidak pakem membahayakan keselamatan berkendara.');
    recs.push('JANGAN gunakan kendaraan. Segera ganti kampas rem dan cek minyak rem (rem cakram).');
  } else if(k==='kelistrikan'){
    severity='med'; badge='Perlu Perhatian';
    diagnoses.push('Masalah kelistrikan bisa dari aki lemah, sekring putus, atau spul/alternator bermasalah.');
    recs.push('Cek tegangan aki, periksa sekring, dan uji spul dengan multimeter.');
  } else if(k==='getaran'){
    severity='med'; badge='Perlu Perhatian';
    diagnoses.push('Getaran berlebih biasanya dari ban tidak seimbang, rantai/belt kendur, atau mounting mesin longgar.');
    recs.push('Lakukan balancing ban, cek rantai, dan kencangkan mounting mesin.');
  } else if(k==='overheat'){
    severity='high'; badge='Perhatian Tinggi';
    diagnoses.push('Overheating dapat disebabkan oleh oli mesin kurang, sistem pendingin bermasalah, atau terlalu lama idle.');
    recs.push('Matikan mesin, tunggu dingin. Cek level oli dan sistem pendingin.');
  } else {
    diagnoses.push('Berdasarkan jawaban Anda, diperlukan pemeriksaan lebih lanjut di bengkel.');
    recs.push('Bawa kendaraan ke mekanik terpercaya untuk diagnosa mendalam.');
  }

  if(servis==='belum_pernah'){recs.push('Kendaraan belum pernah servis — sangat disarankan servis menyeluruh segera.');}
  if(oli==='berkurang'){diagnoses.push('Oli berkurang drastis mengindikasikan kebocoran atau pembakaran oli.');}

  const sevClass = severity==='high'?'severity-high':severity==='med'?'severity-med':'severity-low';
  return {severity, badge, sevClass, diagnoses, recs};
}

function merekLabel(v){return{yamaha:'Yamaha',honda:'Honda',suzuki:'Suzuki',kawasaki:'Kawasaki',vespa:'Vespa',tvs:'TVS',bajaj:'Bajaj',lainnya:'Lainnya'}[v]||v}
function jenisLabel(v){return{bebek:'Motor Bebek',matic:'Motor Matic',sport:'Motor Sport',trail:'Motor Trail'}[v]||v}
function keluhanLabel(v){return{mesin_mati:'Mesin tidak mau hidup',mati_mendadak:'Mesin mati tiba-tiba',brebet:'Motor brebet / tersendat',suara_aneh:'Suara mesin tidak normal',boros_bbm:'Boros bahan bakar',asap_knalpot:'Asap knalpot berlebih',rem_blong:'Rem tidak pakem',kelistrikan:'Masalah kelistrikan',getaran:'Getaran berlebih',overheat:'Mesin cepat panas'}[v]||v}

function showResult(){
  const diag = getDiagnosis();
  document.getElementById('btnBack').style.display='none';
  document.getElementById('btnNext').style.display='none';
  document.getElementById('stepDots').style.display='none';
  document.getElementById('progressWrap')&&(document.getElementById('progressWrap').style.display='none');
  document.querySelector('.progress-wrap').style.display='none';
  document.getElementById('errorMsg').style.display='none';

  const content = document.getElementById('questionContent');
  content.innerHTML=`
    <div class="result-section">
      <div class="result-header">Hasil Diagnosa</div>
      <span class="severity-badge ${diag.sevClass}">${diag.badge}</span>

      <div class="result-item" style="display:flex;gap:16px">
        <div style="flex:1"><div class="result-item-title">Merek</div><div class="result-item-val">${merekLabel(answers.merek)}</div></div>
        <div style="flex:1"><div class="result-item-title">Jenis</div><div class="result-item-val">${jenisLabel(answers.jenis)}</div></div>
      </div>

      <div class="result-item">
        <div class="result-item-title">Keluhan Utama</div>
        <div class="result-item-val">${keluhanLabel(answers.keluhan)}</div>
      </div>

      <div class="diagnosis-box">
        <div class="diagnosis-label">Kemungkinan Masalah</div>
        <div class="diagnosis-text">${diag.diagnoses.map(d=>'• '+d).join('<br>')}</div>
      </div>

      <div class="diagnosis-box">
        <div class="diagnosis-label">Rekomendasi Tindakan</div>
        <div class="diagnosis-text">${diag.recs.map(r=>'✓ '+r).join('<br>')}</div>
      </div>

      <button class="btn-restart" onclick="restart()">Ulangi Diagnosa</button>
    </div>
  `;
}

function restart(){
  document.getElementById('startScreen').style.display='block';
  const card=document.getElementById('diagCard');
  card.classList.remove('visible');
  document.getElementById('btnBack').style.display='';
  document.getElementById('btnNext').style.display='';
  document.getElementById('stepDots').style.display='';
  document.querySelector('.progress-wrap').style.display='';
  currentStep=0; answers={};
}

function setActive(el){
  document.querySelectorAll('.nav-item').forEach(b=>b.classList.remove('active'));
  el.classList.add('active');
}

const style=document.createElement('style');
style.textContent=`@keyframes shake{0%,100%{transform:translateX(0)}20%{transform:translateX(-8px)}40%{transform:translateX(8px)}60%{transform:translateX(-5px)}80%{transform:translateX(5px)}}`;
document.head.appendChild(style);
</script>
</body>
</html>