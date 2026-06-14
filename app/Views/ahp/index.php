<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kalkulator AHP (Analytical Hierarchy Process) interaktif — input kriteria dan alternatif secara bebas untuk menentukan pilihan terbaik.">
    <title>Kalkulator AHP Interaktif | Sistem Pendukung Keputusan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

<style>
/* ============================================================
   TOKENS
============================================================ */
:root{
    --bg:        #07080f;
    --surface:   #0d0f1c;
    --surface2:  #131628;
    --surface3:  #1a1e35;
    --border:    #1e2540;
    --border2:   #283050;
    --primary:   #6c63ff;
    --primary-l: #8b84ff;
    --accent:    #00d4aa;
    --accent-l:  #33ddb8;
    --warn:      #ffa94d;
    --danger:    #ff6b6b;
    --success:   #51cf66;
    --text:      #e8eaf6;
    --text2:     #9aa0c0;
    --text3:     #5a6080;
    --r-sm:8px; --r-md:14px; --r-lg:20px; --r-xl:28px;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;}
body{
    font-family:'Inter',sans-serif;
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
    overflow-x:hidden;
}

/* ============================================================
   BACKGROUND ORBS
============================================================ */
.orbs{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;}
.orbs span{position:absolute;border-radius:50%;filter:blur(90px);opacity:.18;animation:orb 20s ease-in-out infinite;}
.orbs span:nth-child(1){width:550px;height:550px;background:var(--primary);top:-150px;left:-80px;animation-delay:0s;}
.orbs span:nth-child(2){width:400px;height:400px;background:var(--accent);bottom:-100px;right:-50px;animation-delay:-7s;}
.orbs span:nth-child(3){width:300px;height:300px;background:var(--warn);top:40%;left:45%;animation-delay:-14s;}
@keyframes orb{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-50px) scale(1.1);}}

/* ============================================================
   LAYOUT
============================================================ */
.wrapper{
    position:relative;z-index:1;
    max-width:980px;margin:0 auto;padding:0 20px 80px;
}

/* ============================================================
   HEADER
============================================================ */
header{padding:48px 0 36px;text-align:center;}
.badge{
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(108,99,255,.12);border:1px solid rgba(108,99,255,.28);
    border-radius:999px;padding:6px 18px;
    font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
    color:var(--primary-l);margin-bottom:18px;
}
.dot{width:7px;height:7px;border-radius:50%;background:var(--primary);animation:pulse 1.6s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(1.5);}}
header h1{
    font-size:clamp(1.9rem,4.5vw,3rem);font-weight:900;line-height:1.15;
    background:linear-gradient(135deg,#fff 30%,var(--primary-l) 65%,var(--accent));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    margin-bottom:12px;
}
header p{color:var(--text2);font-size:1rem;max-width:520px;margin:0 auto;line-height:1.7;}

/* ============================================================
   STEP INDICATOR
============================================================ */
.step-indicator{
    display:flex;align-items:center;justify-content:center;
    gap:0;margin:0 0 36px;
}
.step-dot{
    display:flex;flex-direction:column;align-items:center;gap:6px;
    flex:1;max-width:160px;position:relative;
}
.step-dot::after{
    content:'';position:absolute;top:18px;left:calc(50% + 22px);
    width:calc(100% - 44px);height:2px;
    background:var(--border2);
    transition:background .4s;
}
.step-dot:last-child::after{display:none;}
.step-dot.completed::after{background:var(--primary);}

.dot-circle{
    width:36px;height:36px;border-radius:50%;
    border:2px solid var(--border2);
    background:var(--surface2);
    display:flex;align-items:center;justify-content:center;
    font-size:.82rem;font-weight:700;color:var(--text3);
    transition:all .35s;z-index:1;
}
.step-dot.active .dot-circle{
    border-color:var(--primary);
    background:linear-gradient(135deg,var(--primary),var(--primary-l));
    color:#fff;
    box-shadow:0 0 18px rgba(108,99,255,.45);
}
.step-dot.completed .dot-circle{
    border-color:var(--accent);background:rgba(0,212,170,.15);color:var(--accent);
}
.dot-label{font-size:.7rem;color:var(--text3);font-weight:600;text-align:center;line-height:1.3;}
.step-dot.active .dot-label{color:var(--text2);}

/* ============================================================
   CARDS
============================================================ */
.card{
    background:var(--surface);border:1px solid var(--border);
    border-radius:var(--r-lg);padding:28px 30px;margin-bottom:20px;
    box-shadow:0 4px 32px rgba(0,0,0,.4);
    transition:border-color .3s;
}
.card:hover{border-color:var(--border2);}
.card-title{
    display:flex;align-items:center;gap:12px;margin-bottom:6px;
}
.card-title h2{font-size:1.15rem;font-weight:800;}
.card-title .icon{
    width:34px;height:34px;border-radius:10px;
    background:linear-gradient(135deg,var(--primary),var(--primary-l));
    display:flex;align-items:center;justify-content:center;
    font-size:.95rem;flex-shrink:0;
    box-shadow:0 0 14px rgba(108,99,255,.35);
}
.card-desc{color:var(--text2);font-size:.85rem;line-height:1.6;margin-bottom:22px;}

/* ============================================================
   FORM ELEMENTS
============================================================ */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;}
.form-group{display:flex;flex-direction:column;gap:7px;}
.form-group label{
    font-size:.78rem;font-weight:700;letter-spacing:.05em;
    text-transform:uppercase;color:var(--text3);
}
.form-group input,
.form-group select{
    background:var(--surface2);
    border:1.5px solid var(--border2);
    border-radius:var(--r-sm);
    padding:11px 14px;
    color:var(--text);
    font-family:'Inter',sans-serif;font-size:.9rem;
    outline:none;transition:border-color .25s,box-shadow .25s;
    width:100%;
}
.form-group input:focus,
.form-group select:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(108,99,255,.2);
}
.form-group select option{background:var(--surface2);}

/* Saaty select */
.saaty-select{
    background:var(--surface3);
    border:1.5px solid var(--border2);
    border-radius:6px;padding:7px 10px;
    color:var(--text);font-family:'Inter',sans-serif;font-size:.82rem;
    outline:none;transition:border-color .25s;cursor:pointer;
    min-width:220px;
}
.saaty-select:focus{border-color:var(--primary);}
.saaty-select option{background:var(--surface3);}

.score-input{
    background:var(--surface3);
    border:1.5px solid var(--border2);
    border-radius:6px;padding:8px 10px;
    color:var(--text);font-size:.88rem;text-align:center;
    width:80px;outline:none;
    transition:border-color .25s,box-shadow .25s;
}
.score-input:focus{
    border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(0,212,170,.18);
}

/* Dynamic name fields */
.names-grid{
    display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;
    margin-bottom:8px;
}
.name-input-wrap{
    display:flex;flex-direction:column;gap:5px;
}
.name-input-wrap span{
    font-size:.72rem;font-weight:600;color:var(--text3);
    text-transform:uppercase;letter-spacing:.05em;
}
.name-input-wrap input{
    background:var(--surface2);border:1.5px solid var(--border2);
    border-radius:var(--r-sm);padding:9px 12px;
    color:var(--text);font-size:.88rem;outline:none;
    transition:border-color .25s;
}
.name-input-wrap input:focus{border-color:var(--primary);}

.section-sub{
    font-size:.8rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
    color:var(--text3);margin:18px 0 10px;
    display:flex;align-items:center;gap:8px;
}
.section-sub::after{
    content:'';flex:1;height:1px;background:var(--border);
}

/* ============================================================
   TABLES
============================================================ */
.table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
table{width:100%;border-collapse:collapse;font-size:.85rem;}
thead th{
    background:rgba(108,99,255,.1);color:var(--primary-l);
    font-weight:700;font-size:.75rem;letter-spacing:.05em;text-transform:uppercase;
    padding:11px 14px;text-align:center;
    border-bottom:2px solid var(--border2);white-space:nowrap;
}
thead th:first-child{text-align:left;}
tbody td{
    padding:12px 14px;text-align:center;
    border-bottom:1px solid var(--border);color:var(--text);
    transition:background .2s;
}
tbody td.row-lbl{text-align:left;font-weight:600;color:var(--text2);white-space:nowrap;}
tbody tr:hover td{background:rgba(108,99,255,.04);}
tbody tr:last-child td{border-bottom:none;}

.matrix-diag{
    font-family:'JetBrains Mono',monospace;font-weight:700;
    color:var(--accent);background:rgba(0,212,170,.06)!important;
    font-size:.9rem;
}
.matrix-recip{
    font-family:'JetBrains Mono',monospace;font-size:.82rem;
    color:var(--text3);background:rgba(255,255,255,.02)!important;
    font-style:italic;
}
.sum-row td{
    background:rgba(0,212,170,.06)!important;
    color:var(--accent)!important;font-weight:700!important;
    border-top:2px solid rgba(0,212,170,.2)!important;
    font-family:'JetBrains Mono',monospace;
}

/* ============================================================
   WEIGHT BARS
============================================================ */
.bar-outer{background:rgba(255,255,255,.06);border-radius:999px;height:7px;overflow:hidden;margin-top:5px;}
.bar-inner{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--accent));
           transition:width 1s cubic-bezier(.22,1,.36,1);}
.mono{font-family:'JetBrains Mono',monospace;font-size:.82rem;}
.val-accent{color:var(--accent);font-weight:700;}
.val-primary{color:var(--primary-l);font-weight:600;}

/* ============================================================
   NAV BUTTONS
============================================================ */
.wizard-nav{
    display:flex;gap:12px;justify-content:flex-end;margin-top:24px;
    flex-wrap:wrap;
}
.btn{
    display:inline-flex;align-items:center;gap:8px;
    padding:12px 24px;border:none;border-radius:var(--r-md);
    font-family:'Inter',sans-serif;font-size:.9rem;font-weight:700;
    cursor:pointer;transition:all .25s;outline:none;
}
.btn-primary{
    background:linear-gradient(135deg,var(--primary),var(--primary-l));
    color:#fff;box-shadow:0 4px 20px rgba(108,99,255,.35);
}
.btn-primary:hover{box-shadow:0 6px 28px rgba(108,99,255,.5);transform:translateY(-2px);}
.btn-secondary{
    background:var(--surface2);border:1.5px solid var(--border2);
    color:var(--text2);
}
.btn-secondary:hover{border-color:var(--primary);color:var(--primary-l);}
.btn-success{
    background:linear-gradient(135deg,#0aaf5a,#51cf66);
    color:#fff;box-shadow:0 4px 20px rgba(81,207,102,.35);
}
.btn-success:hover{box-shadow:0 6px 28px rgba(81,207,102,.5);transform:translateY(-2px);}
.btn-danger{
    background:linear-gradient(135deg,#d63031,var(--danger));
    color:#fff;
}
.btn:disabled{opacity:.5;cursor:not-allowed;transform:none!important;}

/* Loading spinner */
.spinner{
    width:18px;height:18px;border:3px solid rgba(255,255,255,.3);
    border-top-color:#fff;border-radius:50%;
    animation:spin .7s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg);}}

/* ============================================================
   STEP CONTENT
============================================================ */
.step-content{display:none;}
.step-content.active{display:block;animation:fadeUp .4s ease;}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);}}

/* ============================================================
   CONSISTENCY BOXES
============================================================ */
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin:16px 0;}
.stat-box{
    background:var(--surface2);border:1px solid var(--border);
    border-radius:var(--r-md);padding:16px 18px;text-align:center;
}
.stat-box .lbl{font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text3);margin-bottom:8px;}
.stat-box .val{font-family:'JetBrains Mono',monospace;font-size:1.45rem;font-weight:800;}
.stat-box .note{font-size:.72rem;color:var(--text3);margin-top:4px;}
.ok{color:var(--success);}
.warn{color:var(--warn);}
.err{color:var(--danger);}

.cr-status{
    margin-top:16px;padding:13px 18px;border-radius:var(--r-sm);
    display:flex;align-items:center;gap:10px;font-weight:600;font-size:.9rem;
}
.cr-status.ok{background:rgba(81,207,102,.1);border:1px solid rgba(81,207,102,.25);color:var(--success);}
.cr-status.err{background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.25);color:var(--danger);}

/* ============================================================
   PODIUM
============================================================ */
.podium{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin:24px 0;}
.podium-card{
    background:var(--surface);border:1px solid var(--border);
    border-radius:var(--r-lg);padding:26px 20px;text-align:center;
    position:relative;overflow:hidden;
    transition:transform .3s,box-shadow .3s;
}
.podium-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.5);}
.podium-card.gold{border-color:rgba(255,169,77,.4);background:linear-gradient(160deg,#1a150a,var(--surface) 55%);box-shadow:0 0 40px rgba(255,169,77,.1);}
.podium-card.silver{border-color:rgba(160,174,192,.3);}
.podium-card.bronze{border-color:rgba(180,120,60,.25);}
.glow{position:absolute;top:-40px;left:50%;transform:translateX(-50%);width:150px;height:150px;border-radius:50%;filter:blur(38px);pointer-events:none;}
.gold .glow{background:rgba(255,169,77,.25);}
.silver .glow{background:rgba(160,174,192,.12);}
.bronze .glow{background:rgba(180,120,60,.12);}
.podium-emoji{font-size:2rem;margin-bottom:4px;}
.podium-rank{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;}
.gold .podium-rank{color:var(--warn);}
.silver .podium-rank{color:#a0aec0;}
.bronze .podium-rank{color:#b4783c;}
.podium-name{font-size:1.05rem;font-weight:800;margin-bottom:8px;}
.podium-score-lbl{font-size:.68rem;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;}
.podium-score{
    font-family:'JetBrains Mono',monospace;font-size:1.5rem;font-weight:800;
    background:linear-gradient(135deg,var(--primary-l),var(--accent));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.gold .podium-score{background:linear-gradient(135deg,#ffa94d,#ffe066);-webkit-background-clip:text;background-clip:text;}
.score-bar{margin-top:14px;}
.score-bar-out{background:rgba(255,255,255,.06);border-radius:999px;height:5px;overflow:hidden;}
.score-bar-in{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--primary),var(--accent));}
.gold .score-bar-in{background:linear-gradient(90deg,#ffa94d,#ffe066);}

/* ============================================================
   FORMULA BOX
============================================================ */
.formula{
    background:var(--surface2);border:1px solid var(--border);
    border-left:3px solid var(--primary);border-radius:var(--r-sm);
    padding:12px 16px;margin:10px 0 18px;
    font-family:'JetBrains Mono',monospace;font-size:.8rem;
    color:var(--text2);line-height:1.7;
}
.formula strong{color:var(--primary-l);}

/* ============================================================
   INFO ALERT
============================================================ */
.info-box{
    background:rgba(108,99,255,.08);border:1px solid rgba(108,99,255,.22);
    border-radius:var(--r-sm);padding:12px 16px;margin-bottom:16px;
    font-size:.83rem;color:var(--text2);line-height:1.6;
    display:flex;gap:10px;align-items:flex-start;
}
.info-box .icon{font-size:1rem;flex-shrink:0;margin-top:1px;}

/* ============================================================
   TOAST / ERROR
============================================================ */
.toast{
    position:fixed;bottom:24px;right:24px;z-index:999;
    background:var(--danger);color:#fff;
    padding:14px 20px;border-radius:var(--r-md);
    font-size:.88rem;font-weight:600;
    box-shadow:0 8px 24px rgba(255,107,107,.4);
    animation:slideIn .3s ease;
    display:none;
}
@keyframes slideIn{from{transform:translateX(120%);}to{transform:translateX(0);}}

/* ============================================================
   FOOTER
============================================================ */
hr{border:none;border-top:1px solid var(--border);margin:36px 0;}
footer{text-align:center;color:var(--text3);font-size:.8rem;padding-bottom:8px;}
footer span{color:var(--primary-l);}

/* ============================================================
   RESPONSIVE
============================================================ */
@media(max-width:640px){
    .card{padding:18px 16px;}
    .form-grid{grid-template-columns:1fr;}
    .wizard-nav{justify-content:stretch;}
    .wizard-nav .btn{flex:1;justify-content:center;}
    header h1{font-size:1.7rem;}
    .step-dot .dot-label{display:none;}
}
</style>
</head>
<body>

<div class="orbs" aria-hidden="true"><span></span><span></span><span></span></div>
<div class="toast" id="toast"></div>

<div class="wrapper">

<!-- ══════════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════════ -->
<header>
    <div class="badge"><span class="dot"></span>Analytical Hierarchy Process</div>
    <h1>Kalkulator AHP<br>Interaktif</h1>
    <p>Masukkan kriteria dan alternatif secara bebas, lakukan perbandingan berpasangan, dan dapatkan rekomendasi terbaik berdasarkan metode AHP.</p>
</header>

<!-- ══════════════════════════════════════════════════════════
     STEP INDICATOR
══════════════════════════════════════════════════════════ -->
<div class="step-indicator" id="step-indicator">
    <div class="step-dot active" data-step="1">
        <div class="dot-circle" id="dc-1">1</div>
        <div class="dot-label">Setup</div>
    </div>
    <div class="step-dot" data-step="2">
        <div class="dot-circle" id="dc-2">2</div>
        <div class="dot-label">Perbandingan<br>Kriteria</div>
    </div>
    <div class="step-dot" data-step="3">
        <div class="dot-circle" id="dc-3">3</div>
        <div class="dot-label">Nilai<br>Alternatif</div>
    </div>
    <div class="step-dot" data-step="4">
        <div class="dot-circle" id="dc-4">4</div>
        <div class="dot-label">Hasil</div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STEP 1 — SETUP
══════════════════════════════════════════════════════════ -->
<div id="step-1" class="step-content active">
    <div class="card">
        <div class="card-title">
            <div class="icon">⚙️</div>
            <h2>Langkah 1 — Setup Kriteria &amp; Alternatif</h2>
        </div>
        <p class="card-desc">Tentukan jumlah kriteria (2–9) dan jumlah alternatif (2–9), lalu beri nama masing-masing.</p>

        <div class="form-grid">
            <div class="form-group">
                <label>Jumlah Kriteria</label>
                <select id="n-count" onchange="updateNameFields()">
                    <option value="2">2 Kriteria</option>
                    <option value="3" selected>3 Kriteria</option>
                    <option value="4">4 Kriteria</option>
                    <option value="5">5 Kriteria</option>
                    <option value="6">6 Kriteria</option>
                    <option value="7">7 Kriteria</option>
                    <option value="8">8 Kriteria</option>
                    <option value="9">9 Kriteria</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Alternatif</label>
                <select id="m-count" onchange="updateNameFields()">
                    <option value="2">2 Alternatif</option>
                    <option value="3" selected>3 Alternatif</option>
                    <option value="4">4 Alternatif</option>
                    <option value="5">5 Alternatif</option>
                    <option value="6">6 Alternatif</option>
                    <option value="7">7 Alternatif</option>
                    <option value="8">8 Alternatif</option>
                    <option value="9">9 Alternatif</option>
                </select>
            </div>
        </div>

        <div class="section-sub">📋 Nama Kriteria</div>
        <div class="names-grid" id="criteria-names"></div>

        <div class="section-sub">💡 Nama Alternatif</div>
        <div class="names-grid" id="alt-names"></div>
    </div>

    <div class="wizard-nav">
        <button class="btn btn-primary" id="btn-1-next" onclick="step1Next()">
            Selanjutnya → Perbandingan Kriteria
        </button>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STEP 2 — PERBANDINGAN KRITERIA
══════════════════════════════════════════════════════════ -->
<div id="step-2" class="step-content">
    <div class="card">
        <div class="card-title">
            <div class="icon">⚖️</div>
            <h2>Langkah 2 — Matriks Perbandingan Berpasangan Kriteria</h2>
        </div>
        <p class="card-desc">Bandingkan setiap pasang kriteria menggunakan skala Saaty 1–9. Sel diagonal = 1, segitiga bawah terisi otomatis (nilai kebalikan).</p>

        <div class="info-box">
            <span class="icon">💡</span>
            <div>
                <strong>Cara baca:</strong> Nilai pada baris <em>A</em> kolom <em>B</em> menunjukkan seberapa penting <em>A</em> dibandingkan <em>B</em>.
                Nilai &gt; 1 artinya <em>A</em> lebih penting; nilai &lt; 1 artinya <em>B</em> lebih penting.
            </div>
        </div>

        <div id="matrix-container"></div>

        <!-- Saaty legend -->
        <details style="margin-top:16px;">
            <summary style="cursor:pointer;font-size:.82rem;color:var(--text3);font-weight:600;">📖 Panduan Skala Saaty</summary>
            <div class="table-wrap" style="margin-top:12px;">
                <table style="font-size:.78rem;">
                    <thead><tr><th>Nilai</th><th>Definisi</th></tr></thead>
                    <tbody>
                        <tr><td class="mono" style="color:var(--accent);">1</td><td>Sama penting</td></tr>
                        <tr><td class="mono">2</td><td>Sedikit lebih penting</td></tr>
                        <tr><td class="mono">3</td><td>Cukup lebih penting</td></tr>
                        <tr><td class="mono">4</td><td>Lebih dari cukup penting</td></tr>
                        <tr><td class="mono">5</td><td>Lebih penting</td></tr>
                        <tr><td class="mono">6</td><td>Cukup jauh lebih penting</td></tr>
                        <tr><td class="mono">7</td><td>Sangat lebih penting</td></tr>
                        <tr><td class="mono">8</td><td>Sangat-sangat lebih penting</td></tr>
                        <tr><td class="mono" style="color:var(--warn);">9</td><td>Mutlak lebih penting</td></tr>
                        <tr><td class="mono" style="color:var(--text3);">1/x</td><td>Kebalikan dari nilai di atas</td></tr>
                    </tbody>
                </table>
            </div>
        </details>
    </div>

    <div class="wizard-nav">
        <button class="btn btn-secondary" onclick="goToStep(1)">← Kembali</button>
        <button class="btn btn-primary" onclick="step2Next()">Selanjutnya → Nilai Alternatif</button>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STEP 3 — PENILAIAN ALTERNATIF
══════════════════════════════════════════════════════════ -->
<div id="step-3" class="step-content">
    <div class="card">
        <div class="card-title">
            <div class="icon">📊</div>
            <h2>Langkah 3 — Penilaian Alternatif per Kriteria</h2>
        </div>
        <p class="card-desc">Berikan nilai numerik untuk setiap alternatif berdasarkan tiap kriteria. Nilai bisa berupa angka bebas positif (misal skala 1–9, 1–100, harga, dsb). Nilai akan dinormalisasi secara otomatis.</p>

        <div class="info-box">
            <span class="icon">📌</span>
            <div>Gunakan nilai yang <strong>konsisten</strong>: semakin besar = semakin baik untuk kriteria tersebut. Nilai minimal adalah 0.01.</div>
        </div>

        <div id="scoring-container"></div>
    </div>

    <div class="wizard-nav">
        <button class="btn btn-secondary" onclick="goToStep(2)">← Kembali</button>
        <button class="btn btn-success" id="btn-calculate" onclick="calculateAHP()">
            🔢 Hitung AHP
        </button>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STEP 4 — HASIL
══════════════════════════════════════════════════════════ -->
<div id="step-4" class="step-content">
    <div id="results-container"></div>

    <div class="wizard-nav" style="margin-top:8px;">
        <button class="btn btn-secondary" onclick="goToStep(3)">← Kembali ke Penilaian</button>
        <button class="btn btn-danger" onclick="resetAll()">🔄 Hitung Ulang (Reset)</button>
    </div>
</div>

<hr>
<footer><p>Kalkulator AHP Interaktif · <span>CodeIgniter 4</span> · &copy; <?= date('Y') ?></p></footer>

</div><!-- /wrapper -->

<!-- ══════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════ -->
<script>
// ──────────────────────────────────────────────────────────
// STATE
// ──────────────────────────────────────────────────────────
const state = {
    n: 3, m: 3,
    criteriaNames: [],
    altNames: [],
    matrix: [],
    altScores: [],
};

// ──────────────────────────────────────────────────────────
// SAATY SCALE
// ──────────────────────────────────────────────────────────
const SAATY = [
    { v: 9,         label: '9 — Mutlak lebih penting' },
    { v: 8,         label: '8 — Sangat-sangat lebih penting' },
    { v: 7,         label: '7 — Sangat lebih penting' },
    { v: 6,         label: '6 — Lebih dari cukup penting' },
    { v: 5,         label: '5 — Lebih penting' },
    { v: 4,         label: '4 — Cukup lebih penting' },
    { v: 3,         label: '3 — Sedikit lebih penting' },
    { v: 2,         label: '2 — Hampir lebih penting' },
    { v: 1,         label: '1 — Sama penting' },
    { v: 1/2,       label: '1/2 — Hampir kurang penting' },
    { v: 1/3,       label: '1/3 — Sedikit kurang penting' },
    { v: 1/4,       label: '1/4 — Cukup kurang penting' },
    { v: 1/5,       label: '1/5 — Kurang penting' },
    { v: 1/6,       label: '1/6 — Lebih dari cukup kurang' },
    { v: 1/7,       label: '1/7 — Sangat kurang penting' },
    { v: 1/8,       label: '1/8 — Sangat-sangat kurang' },
    { v: 1/9,       label: '1/9 — Mutlak kurang penting' },
];

function saatyOptions(selectedVal = 1) {
    return SAATY.map(s =>
        `<option value="${s.v}" ${Math.abs(s.v - selectedVal) < 1e-9 ? 'selected' : ''}>${s.label}</option>`
    ).join('');
}

function formatFrac(v) {
    if (Math.abs(v - 1) < 0.001) return '1';
    for (let d = 2; d <= 9; d++) {
        if (Math.abs(v - d)   < 0.01) return String(d);
        if (Math.abs(v - 1/d) < 0.01) return `1/${d}`;
    }
    return v.toFixed(3);
}

// ──────────────────────────────────────────────────────────
// WIZARD NAVIGATION
// ──────────────────────────────────────────────────────────
let currentStep = 1;

function goToStep(step) {
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    document.getElementById(`step-${step}`).classList.add('active');

    document.querySelectorAll('.step-dot').forEach((dot, idx) => {
        dot.classList.remove('active', 'completed');
        const s = idx + 1;
        if (s < step)       dot.classList.add('completed');
        else if (s === step) dot.classList.add('active');
    });

    // Connector lines
    document.querySelectorAll('.step-dot').forEach((dot, idx) => {
        if (idx + 1 < step) dot.classList.add('completed');
    });

    // Dot icons for completed
    for (let i = 1; i <= 4; i++) {
        const dc = document.getElementById(`dc-${i}`);
        dc.textContent = (i < step) ? '✓' : String(i);
    }

    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ──────────────────────────────────────────────────────────
// STEP 1 — SETUP
// ──────────────────────────────────────────────────────────
function updateNameFields() {
    const n = parseInt(document.getElementById('n-count').value);
    const m = parseInt(document.getElementById('m-count').value);
    state.n = n; state.m = m;

    const cn = document.getElementById('criteria-names');
    cn.innerHTML = '';
    for (let i = 0; i < n; i++) {
        cn.innerHTML += `
        <div class="name-input-wrap">
            <span>Kriteria ${i + 1}</span>
            <input type="text" id="cname-${i}"
                   placeholder="Nama kriteria ${i + 1}"
                   value="${state.criteriaNames[i] || ''}">
        </div>`;
    }

    const an = document.getElementById('alt-names');
    an.innerHTML = '';
    for (let i = 0; i < m; i++) {
        an.innerHTML += `
        <div class="name-input-wrap">
            <span>Alternatif ${i + 1}</span>
            <input type="text" id="aname-${i}"
                   placeholder="Nama alternatif ${i + 1}"
                   value="${state.altNames[i] || ''}">
        </div>`;
    }
}

function collectStep1() {
    const n = state.n, m = state.m;
    state.criteriaNames = [];
    state.altNames = [];
    for (let i = 0; i < n; i++) {
        const v = document.getElementById(`cname-${i}`).value.trim();
        state.criteriaNames.push(v || `Kriteria ${i + 1}`);
    }
    for (let i = 0; i < m; i++) {
        const v = document.getElementById(`aname-${i}`).value.trim();
        state.altNames.push(v || `Alternatif ${i + 1}`);
    }
}

function step1Next() {
    collectStep1();

    // Validate no empty
    let ok = true;
    for (let i = 0; i < state.n; i++) {
        if (!state.criteriaNames[i]) { ok = false; break; }
    }
    if (ok) {
        for (let i = 0; i < state.m; i++) {
            if (!state.altNames[i]) { ok = false; break; }
        }
    }

    generateMatrix();
    goToStep(2);
}

// ──────────────────────────────────────────────────────────
// STEP 2 — MATRIKS KRITERIA
// ──────────────────────────────────────────────────────────
function generateMatrix() {
    const n = state.n;
    const names = state.criteriaNames;
    let html = '<div class="table-wrap"><table>';

    // Header
    html += '<thead><tr><th></th>';
    for (let j = 0; j < n; j++) {
        html += `<th title="${names[j]}">${truncate(names[j], 14)}</th>`;
    }
    html += '</tr></thead><tbody>';

    for (let i = 0; i < n; i++) {
        html += `<tr><td class="row-lbl" title="${names[i]}">${truncate(names[i], 14)}</td>`;
        for (let j = 0; j < n; j++) {
            if (i === j) {
                html += `<td class="matrix-diag">1</td>`;
            } else if (i < j) {
                const prev = (state.matrix[i] && state.matrix[i][j]) ? state.matrix[i][j] : 1;
                html += `<td>
                    <select class="saaty-select" id="m-${i}-${j}"
                            onchange="updateReciprocal(${i},${j})">
                        ${saatyOptions(prev)}
                    </select>
                </td>`;
            } else {
                // lower triangle — auto
                html += `<td class="matrix-recip" id="mr-${i}-${j}">1</td>`;
            }
        }
        html += '</tr>';
    }
    html += '</tbody></table></div>';
    document.getElementById('matrix-container').innerHTML = html;

    // Init reciprocals
    for (let i = 0; i < n; i++) {
        for (let j = i + 1; j < n; j++) {
            updateReciprocal(i, j);
        }
    }
}

function updateReciprocal(i, j) {
    const sel = document.getElementById(`m-${i}-${j}`);
    if (!sel) return;
    const v = parseFloat(sel.value);
    const recip = 1 / v;
    const cell = document.getElementById(`mr-${j}-${i}`);
    if (cell) cell.textContent = formatFrac(recip);
}

function collectMatrix() {
    const n = state.n;
    state.matrix = Array.from({ length: n }, () => Array(n).fill(1));
    for (let i = 0; i < n; i++) {
        for (let j = i + 1; j < n; j++) {
            const v = parseFloat(document.getElementById(`m-${i}-${j}`).value);
            state.matrix[i][j] = v;
            state.matrix[j][i] = 1 / v;
        }
    }
}

function step2Next() {
    collectMatrix();
    generateScoringTable();
    goToStep(3);
}

// ──────────────────────────────────────────────────────────
// STEP 3 — PENILAIAN ALTERNATIF
// ──────────────────────────────────────────────────────────
function generateScoringTable() {
    const n = state.n, m = state.m;
    const cNames = state.criteriaNames;
    const aNames = state.altNames;

    let html = '<div class="table-wrap"><table>';
    html += '<thead><tr><th>Alternatif</th>';
    for (let c = 0; c < n; c++) {
        html += `<th title="${cNames[c]}">${truncate(cNames[c], 14)}</th>`;
    }
    html += '</tr></thead><tbody>';

    for (let a = 0; a < m; a++) {
        html += `<tr><td class="row-lbl" title="${aNames[a]}">${truncate(aNames[a], 18)}</td>`;
        for (let c = 0; c < n; c++) {
            const prev = (state.altScores[c] && state.altScores[c][a] != null)
                          ? state.altScores[c][a] : 1;
            html += `<td>
                <input type="number" class="score-input" id="sc-${c}-${a}"
                       min="0.01" step="0.01" value="${prev}">
            </td>`;
        }
        html += '</tr>';
    }
    html += '</tbody></table></div>';
    document.getElementById('scoring-container').innerHTML = html;
}

function collectScores() {
    const n = state.n, m = state.m;
    state.altScores = Array.from({ length: n }, () => Array(m).fill(1));
    for (let c = 0; c < n; c++) {
        for (let a = 0; a < m; a++) {
            const el = document.getElementById(`sc-${c}-${a}`);
            state.altScores[c][a] = parseFloat(el?.value) || 1;
        }
    }
}

// ──────────────────────────────────────────────────────────
// CALCULATE (AJAX)
// ──────────────────────────────────────────────────────────
async function calculateAHP() {
    collectScores();

    const btn = document.getElementById('btn-calculate');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Menghitung...';

    const payload = {
        criteria:      state.criteriaNames,
        alternatives:  state.altNames,
        criteriaMatrix: state.matrix,
        altScores:     state.altScores,
    };

    try {
        const resp = await fetch('/calculate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        if (!resp.ok) {
            const err = await resp.json();
            throw new Error(err.error || 'Terjadi kesalahan pada server.');
        }

        const data = await resp.json();
        renderResults(data);
        goToStep(4);
    } catch (e) {
        showToast('❌ ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '🔢 Hitung AHP';
    }
}

// ──────────────────────────────────────────────────────────
// RENDER RESULTS
// ──────────────────────────────────────────────────────────
function renderResults(d) {
    const n = d.n, m = d.m;
    const cNames = d.criteriaNames;
    const aNames = d.altNames;
    const maxW   = Math.max(...d.weights);
    const maxS   = Math.max(...d.finalScores);

    // ── Ranking order ────────────────────────────────────
    const rankOrder = Array.from({ length: m }, (_, i) => i)
        .sort((a, b) => d.finalScores[b] - d.finalScores[a]);

    const podiumClass = ['gold', 'silver', 'bronze'];
    const podiumEmoji = ['🥇', '🥈', '🥉'];
    const podiumLabel = ['Peringkat 1 – Terbaik', 'Peringkat 2', 'Peringkat 3'];

    // ──────────────────────────────────────────────────
    // PODIUM
    // ──────────────────────────────────────────────────
    let podiumHTML = '<div class="podium">';
    rankOrder.forEach((altIdx, rank) => {
        const sc = d.finalScores[altIdx];
        const pct = ((sc / maxS) * 100).toFixed(1);
        const cls = podiumClass[rank] || '';
        const em  = podiumEmoji[rank] || `#${rank+1}`;
        const lbl = podiumLabel[rank] || `Peringkat ${rank+1}`;
        podiumHTML += `
        <div class="podium-card ${cls}">
            <div class="glow"></div>
            <div class="podium-emoji">${em}</div>
            <div class="podium-rank">${lbl}</div>
            <div class="podium-name">${esc(aNames[altIdx])}</div>
            <div class="podium-score-lbl">Skor AHP</div>
            <div class="podium-score">${sc.toFixed(4)}</div>
            <div class="score-bar">
                <div class="score-bar-out">
                    <div class="score-bar-in" style="width:${pct}%"></div>
                </div>
            </div>
        </div>`;
    });
    podiumHTML += '</div>';

    // ──────────────────────────────────────────────────
    // MATRIKS PERBANDINGAN
    // ──────────────────────────────────────────────────
    let matHTML = '<div class="table-wrap"><table><thead><tr><th></th>';
    cNames.forEach(cn => { matHTML += `<th>${esc(cn)}</th>`; });
    matHTML += '</tr></thead><tbody>';
    for (let i = 0; i < n; i++) {
        matHTML += `<tr><td class="row-lbl">${esc(cNames[i])}</td>`;
        for (let j = 0; j < n; j++) {
            const v = d.matrix[i][j];
            const cls = i === j ? 'class="matrix-diag"' : (i < j ? '' : 'class="matrix-recip"');
            matHTML += `<td ${cls}>${formatFrac(v)}</td>`;
        }
        matHTML += '</tr>';
    }
    // Col sums
    matHTML += '<tr class="sum-row"><td>Jumlah Kolom</td>';
    d.colSums.forEach(s => { matHTML += `<td>${s.toFixed(4)}</td>`; });
    matHTML += '</tr></tbody></table></div>';

    // ──────────────────────────────────────────────────
    // NORMALISASI + BOBOT
    // ──────────────────────────────────────────────────
    let normHTML = '<div class="table-wrap"><table><thead><tr><th>Kriteria</th>';
    cNames.forEach(cn => { normHTML += `<th>${esc(cn)}</th>`; });
    normHTML += '<th>Bobot (Wi)</th></tr></thead><tbody>';
    for (let i = 0; i < n; i++) {
        normHTML += `<tr><td class="row-lbl">${esc(cNames[i])}</td>`;
        d.normalMatrix[i].forEach(v => { normHTML += `<td class="mono">${v.toFixed(4)}</td>`; });
        const wpct = ((d.weights[i] / maxW) * 100).toFixed(1);
        normHTML += `<td>
            <span class="val-accent">${d.weights[i].toFixed(4)}</span>
            <div class="bar-outer"><div class="bar-inner" style="width:${wpct}%"></div></div>
        </td>`;
        normHTML += '</tr>';
    }
    normHTML += '</tbody></table></div>';

    // ──────────────────────────────────────────────────
    // WSV TABLE (untuk CI/CR)
    // ──────────────────────────────────────────────────
    let wsvHTML = '<div class="table-wrap"><table><thead><tr><th>Kriteria</th><th>Bobot (Wi)</th><th>WSV</th><th>WSV / Wi (λ)</th></tr></thead><tbody>';
    for (let i = 0; i < n; i++) {
        const lambda = d.wsv[i] / d.weights[i];
        wsvHTML += `<tr>
            <td class="row-lbl">${esc(cNames[i])}</td>
            <td class="mono">${d.weights[i].toFixed(4)}</td>
            <td class="mono">${d.wsv[i].toFixed(4)}</td>
            <td class="mono val-primary">${lambda.toFixed(4)}</td>
        </tr>`;
    }
    wsvHTML += '</tbody></table></div>';

    // CI/CR stat boxes
    const crCls = d.consistent ? 'ok' : 'err';
    const lambdaCls = 'ok';
    const ciCls = Math.abs(d.ci) < 0.15 ? 'ok' : 'warn';
    let statHTML = `
    <div class="stat-grid">
        <div class="stat-box">
            <div class="lbl">λ max</div>
            <div class="val ${lambdaCls}">${d.lambdaMax.toFixed(4)}</div>
            <div class="note">n = ${n}</div>
        </div>
        <div class="stat-box">
            <div class="lbl">CI</div>
            <div class="val ${ciCls}">${d.ci.toFixed(4)}</div>
            <div class="note">(λmax − n) / (n−1)</div>
        </div>
        <div class="stat-box">
            <div class="lbl">RI</div>
            <div class="val ok">${d.ri.toFixed(2)}</div>
            <div class="note">Nilai tabel, n=${n}</div>
        </div>
        <div class="stat-box">
            <div class="lbl">CR</div>
            <div class="val ${crCls}">${d.cr.toFixed(4)}</div>
            <div class="note">CI / RI</div>
        </div>
    </div>
    <div class="cr-status ${d.consistent ? 'ok' : 'err'}">
        <span>${d.consistent ? '✅' : '❌'}</span>
        <span>CR = ${d.cr.toFixed(4)} ${d.consistent ? '≤' : '>'} 0.1 →
            <strong>${d.consistent ? 'Matriks KONSISTEN. Penilaian dapat digunakan.' : 'Matriks TIDAK KONSISTEN. Perlu direvisi.'}</strong>
        </span>
    </div>`;

    // ──────────────────────────────────────────────────
    // SKOR ALTERNATIF (raw)
    // ──────────────────────────────────────────────────
    let rawHTML = '<div class="table-wrap"><table><thead><tr><th>Alternatif</th>';
    cNames.forEach(cn => { rawHTML += `<th>${esc(cn)}</th>`; });
    rawHTML += '</tr></thead><tbody>';
    for (let a = 0; a < m; a++) {
        rawHTML += `<tr><td class="row-lbl">${esc(aNames[a])}</td>`;
        for (let c = 0; c < n; c++) {
            rawHTML += `<td class="mono">${d.altScores[c][a]}</td>`;
        }
        rawHTML += '</tr>';
    }
    // Totals
    rawHTML += '<tr class="sum-row"><td>Total Kolom</td>';
    for (let c = 0; c < n; c++) {
        const total = d.altScores[c].reduce((a, b) => a + b, 0);
        rawHTML += `<td>${total.toFixed(2)}</td>`;
    }
    rawHTML += '</tr></tbody></table></div>';

    // ──────────────────────────────────────────────────
    // NORMALISASI ALTERNATIF
    // ──────────────────────────────────────────────────
    let altNormHTML = '<div class="table-wrap"><table><thead><tr><th>Alternatif</th>';
    cNames.forEach(cn => { altNormHTML += `<th>${esc(cn)}</th>`; });
    altNormHTML += '</tr></thead><tbody>';
    for (let a = 0; a < m; a++) {
        altNormHTML += `<tr><td class="row-lbl">${esc(aNames[a])}</td>`;
        for (let c = 0; c < n; c++) {
            altNormHTML += `<td class="mono">${d.altNormal[c][a].toFixed(4)}</td>`;
        }
        altNormHTML += '</tr>';
    }
    altNormHTML += '</tbody></table></div>';

    // ──────────────────────────────────────────────────
    // TABEL SKOR AKHIR
    // ──────────────────────────────────────────────────
    let finalHTML = '<div class="table-wrap"><table><thead><tr><th>Alternatif</th>';
    cNames.forEach((cn, c) => {
        finalHTML += `<th>W${c+1}(${d.weights[c].toFixed(3)}) × V</th>`;
    });
    finalHTML += '<th>Skor Akhir</th><th>Rank</th></tr></thead><tbody>';

    const rankEmojis = ['🥇','🥈','🥉'];
    const rankColors = ['#ffa94d','#a0aec0','#b4783c'];
    for (let a = 0; a < m; a++) {
        const rank = d.ranking[a];
        const isWinner = rank === 1;
        finalHTML += `<tr>
            <td class="row-lbl">${esc(aNames[a])}${isWinner ? ' <span style="background:rgba(255,169,77,.15);border:1px solid rgba(255,169,77,.35);color:var(--warn);font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:999px;">🏆 Terbaik</span>' : ''}</td>`;
        for (let c = 0; c < n; c++) {
            finalHTML += `<td class="mono">${(d.weights[c] * d.altWeights[c][a]).toFixed(4)}</td>`;
        }
        const rclr = rankColors[rank-1] || 'var(--text2)';
        finalHTML += `
            <td class="mono val-accent">${d.finalScores[a].toFixed(4)}</td>
            <td style="font-weight:800;font-size:1.05rem;color:${rclr}">${rankEmojis[rank-1] || '#'}${rank}</td>
        </tr>`;
    }
    finalHTML += '</tbody></table></div>';

    // ──────────────────────────────────────────────────
    // KESIMPULAN
    // ──────────────────────────────────────────────────
    const winnerIdx  = rankOrder[0];
    const winnerName = esc(aNames[winnerIdx]);
    const winnerScore = d.finalScores[winnerIdx].toFixed(4);
    const critWeightStr = cNames.map((cn, i) =>
        `<strong style="color:var(--accent)">${esc(cn)} (${(d.weights[i]*100).toFixed(1)}%)</strong>`
    ).join(', ');

    const conclusionHTML = `
    <div class="card" style="border-color:rgba(255,169,77,.3);background:linear-gradient(135deg,rgba(255,169,77,.07),var(--surface));">
        <h3 style="font-size:.95rem;font-weight:800;margin-bottom:10px;color:var(--warn);">📝 Kesimpulan</h3>
        <p style="color:var(--text2);line-height:1.8;font-size:.9rem;">
            Berdasarkan analisis <strong style="color:var(--text)">AHP</strong> dengan ${n} kriteria
            (${critWeightStr}) dan ${m} alternatif, nilai
            Consistency Ratio <strong>CR = ${d.cr.toFixed(4)}</strong>
            ${d.consistent
                ? '≤ 0.1 menunjukkan penilaian <strong style="color:var(--success)">konsisten</strong>.'
                : '> 0.1 menunjukkan penilaian <strong style="color:var(--danger)">tidak konsisten</strong> — pertimbangkan revisi nilai perbandingan kriteria.'
            }
            Alternatif dengan skor tertinggi adalah
            <strong style="color:var(--warn);font-size:1rem">${winnerName}</strong>
            dengan skor AHP <strong style="color:var(--accent)">${winnerScore}</strong>,
            sehingga direkomendasikan sebagai pilihan terbaik.
        </p>
    </div>`;

    // ──────────────────────────────────────────────────
    // ASSEMBLE ALL
    // ──────────────────────────────────────────────────
    const container = document.getElementById('results-container');
    container.innerHTML = `
    <!-- ─── HEADING ─── -->
    <div class="card" style="text-align:center;background:linear-gradient(135deg,rgba(108,99,255,.08),var(--surface));">
        <div class="card-title" style="justify-content:center;">
            <div class="icon" style="background:linear-gradient(135deg,#ffa94d,#ffe066);">🏆</div>
            <h2>Hasil Analisis AHP</h2>
        </div>
        <p class="card-desc" style="margin-bottom:0;">Peringkat berdasarkan skor tertinggi dari perhitungan Analytical Hierarchy Process</p>
    </div>

    <!-- ─── PODIUM ─── -->
    ${podiumHTML}
    ${conclusionHTML}

    <!-- ─── MATRIKS KRITERIA ─── -->
    <div class="card">
        <div class="card-title"><div class="icon">📋</div><h2>Step 1 — Matriks Perbandingan Berpasangan</h2></div>
        <p class="card-desc">Matriks perbandingan kriteria lengkap (termasuk diagonal &amp; segitiga bawah)</p>
        ${matHTML}
    </div>

    <!-- ─── NORMALISASI KRITERIA ─── -->
    <div class="card">
        <div class="card-title"><div class="icon">📐</div><h2>Step 2 — Normalisasi &amp; Bobot Kriteria</h2></div>
        <div class="formula"><strong>Normalisasi</strong> = Nilai / Jumlah Kolom &nbsp;|&nbsp; <strong>Bobot</strong> = Rata-rata Baris</div>
        ${normHTML}
    </div>

    <!-- ─── KONSISTENSI ─── -->
    <div class="card">
        <div class="card-title"><div class="icon">✅</div><h2>Step 3 — Uji Konsistensi</h2></div>
        <div class="formula"><strong>λmax</strong> = Σ(WSV/Wi)/n &nbsp;|&nbsp; <strong>CI</strong> = (λmax−n)/(n−1) &nbsp;|&nbsp; <strong>CR</strong> = CI/RI</div>
        ${wsvHTML}
        ${statHTML}
    </div>

    <!-- ─── SKOR ALTERNATIF ─── -->
    <div class="card">
        <div class="card-title"><div class="icon">📊</div><h2>Step 4 — Penilaian &amp; Normalisasi Alternatif</h2></div>
        <p style="font-size:.82rem;color:var(--text2);font-weight:600;margin-bottom:8px;">Skor Mentah</p>
        ${rawHTML}
        <p style="font-size:.82rem;color:var(--text2);font-weight:600;margin:16px 0 8px;">Normalisasi (Nilai / Total Kolom)</p>
        ${altNormHTML}
    </div>

    <!-- ─── SKOR AKHIR ─── -->
    <div class="card">
        <div class="card-title"><div class="icon">🎯</div><h2>Step 5 — Skor Akhir &amp; Peringkat</h2></div>
        <div class="formula"><strong>Skor Akhir</strong> = Σ (Bobot Kriteria × Bobot Alternatif per Kriteria)</div>
        ${finalHTML}
    </div>`;

    // Animate bars
    setTimeout(() => {
        document.querySelectorAll('#results-container .bar-inner, #results-container .score-bar-in').forEach(b => {
            const w = b.style.width;
            b.style.width = '0%';
            requestAnimationFrame(() => { b.style.width = w; });
        });
    }, 100);
}

// ──────────────────────────────────────────────────────────
// UTILITIES
// ──────────────────────────────────────────────────────────
function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function truncate(str, len) {
    return str.length > len ? str.slice(0, len) + '…' : str;
}
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 4000);
}
function resetAll() {
    state.criteriaNames = [];
    state.altNames = [];
    state.matrix = [];
    state.altScores = [];
    document.getElementById('n-count').value = 3;
    document.getElementById('m-count').value = 3;
    updateNameFields();
    goToStep(1);
}

// ──────────────────────────────────────────────────────────
// INIT
// ──────────────────────────────────────────────────────────
updateNameFields();
</script>
</body>
</html>
