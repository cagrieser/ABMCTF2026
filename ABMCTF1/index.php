<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ABMSports TV — FIFA Dünya Kupası Canlı Yayın</title>
  <meta name="description" content="ABMSports TV ile FIFA Dünya Kupası maçlarını HD kalitede canlı izleyin.">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📡</text></svg>">
</head>
<body>

  <div class="bg-grid"></div>
  <div class="bg-glow bg-glow--1"></div>
  <div class="bg-glow bg-glow--2"></div>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar">
    <a href="#" class="navbar__brand">
      <div class="navbar__logo">ABM</div>
      <div class="navbar__title">ABMSports <span>TV</span></div>
    </a>
    <ul class="navbar__links">
      <li><a href="#" id="navLive">Canlı Maçlar</a></li>
      <li><a href="#" id="navFixture">Fikstür</a></li>
      <li><a href="#" id="navGroups">Gruplar</a></li>
      <li><a href="#" id="navStandings">Puan Durumu</a></li>
      <li><a href="#" id="navHighlights">Özetler</a></li>
    </ul>
    <div style="display:flex;align-items:center;gap:10px">
      <!-- Search -->
      <div style="position:relative">
        <input type="text" id="searchInput" placeholder="Takım veya maç ara..." style="padding:7px 14px 7px 32px;border-radius:8px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-size:0.8rem;width:180px;font-family:inherit;outline:none;transition:var(--transition)" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:0.8rem">🔍</span>
      </div>
      <!-- Notifications Bell -->
      <button class="btn btn--sm" id="btnNotif" style="position:relative">
        🔔
        <span id="notifBadge" style="position:absolute;top:-4px;right:-4px;width:16px;height:16px;border-radius:50%;background:var(--red);color:#fff;font-size:0.6rem;display:flex;align-items:center;justify-content:center;font-weight:700">2</span>
      </button>
      <!-- Login -->
      <button class="btn btn--sm" id="btnLogin">👤 Giriş</button>
      <!-- Live Badge -->
      <div class="navbar__live-badge">
        <div class="navbar__live-dot"></div>
        5 CANLI YAYIN
      </div>
    </div>
  </nav>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main">

    <div class="waf-banner">
      <span class="waf-banner__icon">🛡️</span>
      <span class="waf-banner__text">Bu site <span>CloudShield Pro WAF</span> ile korunmaktadır. Tüm istekler filtrelenmektedir.</span>
    </div>

    <div class="content-grid">

      <!-- ===== LEFT: VIDEO PLAYER ===== -->
      <div class="player">
        <div class="player__screen">
          <video id="streamVideo" class="player__canvas" autoplay muted loop playsinline>
            <source src="stream.php?source=internal-cdn&ch=1&stream=live" type="video/mp4">
          </video>
          <div class="player__noise"></div>
          <div class="player__scanline"></div>
          <div class="player__live-tag">CANLI</div>
          <div class="player__viewers-tag">
            <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
            <span id="viewerCount">3.200</span>
          </div>
          <div class="player__quality-tag">1080p60</div>
        </div>

        <!-- Player Controls -->
        <div class="player__controls">
          <div class="player__info">
            <div class="player__match-title">🇹🇷 Türkiye vs Gürcistan 🇬🇪</div>
            <div class="player__match-league">FIFA Dünya Kupası 2026 — Grup F · <span id="matchTime">67'</span></div>
          </div>
          <div class="player__actions">
            <button class="btn btn--accent" id="btnStatus">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              Sistem Durumu
            </button>
            <button class="btn" id="btnSound">🔇 Ses Aç</button>
            <button class="btn" id="btnQuality">🎬 Kalite</button>
            <button class="btn" id="btnReport">⚠️ Bildir</button>
            <button class="btn" data-fake>⛶ Tam Ekran</button>
          </div>
        </div>

        <!-- ===== LIVE SCORES SECTION ===== -->
        <div class="card" style="margin-top:20px; background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255,255,255,0.05);">
          <div class="card__header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px;">
            <div class="card__title" style="font-size: 0.9rem;"><span class="live-indicator"></span> Diğer Canlı Maçlar</div>
            <button class="btn" id="btnGoalSound" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px; background: rgba(34, 197, 94, 0.1); color: var(--green); border: 1px solid rgba(34, 197, 94, 0.2);">🔊 Gol Sesi Açık</button>
          </div>
          <div class="card__body" style="padding: 15px;">
            <div id="liveScoresGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 12px;">
              <!-- Scores injected via JS -->
            </div>
          </div>
        </div>

      </div>

      <!-- ===== RIGHT: SIDEBAR ===== -->
      <div class="sidebar">

        <!-- Live Chat -->
        <div class="card">
          <div class="card__header">
            <div class="card__title"><span class="card__title-icon">💬</span> Canlı Sohbet</div>
            <span class="card__badge card__badge--green" id="chatOnline">0 Çevrimiçi</span>
          </div>
          <div class="card__body" style="padding:0">
            <div id="chatMessages" style="height:200px;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:6px;font-size:0.8rem"></div>
            <div style="display:flex;gap:6px;padding:8px 10px;border-top:1px solid var(--border)">
              <input type="text" id="chatInput" placeholder="Mesaj yaz..." style="flex:1;padding:7px 12px;border-radius:6px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-size:0.78rem;font-family:inherit;outline:none">
              <button class="btn btn--accent btn--sm" id="btnChatSend">Gönder</button>
            </div>
          </div>
        </div>

        <!-- Server List -->
        <div class="card">
          <div class="card__header">
            <div class="card__title"><span class="card__title-icon">🌐</span> Sunucu Seçimi</div>
            <span class="card__badge card__badge--green">4 Aktif</span>
          </div>
          <div class="card__body">
            <div class="server-list">
              <div class="server-item server-item--active" data-name="Avrupa-1" data-source="internal-cdn"><span class="server-item__name"><span class="server-item__dot"></span> Avrupa #1</span><span class="server-item__ping">24ms</span></div>
              <div class="server-item" data-name="Avrupa-2" data-source="cdn-backup-01"><span class="server-item__name"><span class="server-item__dot"></span> Avrupa #2</span><span class="server-item__ping">38ms</span></div>
              <div class="server-item" data-name="ABD-Doğu" data-source="cdn-backup-02"><span class="server-item__name"><span class="server-item__dot server-item__dot--yellow"></span> ABD Doğu</span><span class="server-item__ping">112ms</span></div>
              <div class="server-item" data-name="Asya" data-source="cdn-backup-02"><span class="server-item__name"><span class="server-item__dot server-item__dot--red"></span> Asya Pasifik</span><span class="server-item__ping">185ms</span></div>
            </div>
          </div>
        </div>

        <!-- Match Schedule -->
        <div class="card">
          <div class="card__header">
            <div class="card__title"><span class="card__title-icon">🏆</span> Dünya Kupası Maçları</div>
            <span class="card__badge card__badge--red">CANLI</span>
          </div>
          <div class="card__body">
            <div class="match-list">
              <div class="match-item" data-fake><div class="match-item__teams">🇹🇷 TUR vs GEO 🇬🇪</div><div class="match-item__meta"><span class="match-item__live">CANLI</span><span class="match-item__time">67'</span></div></div>
              <div class="match-item" data-fake><div class="match-item__teams">🇧🇷 BRA vs SRB 🇷🇸</div><div class="match-item__meta"><span class="match-item__live">CANLI</span><span class="match-item__time">42'</span></div></div>
              <div class="match-item" data-fake><div class="match-item__teams">🇩🇪 GER vs JPN 🇯🇵</div><div class="match-item__meta"><span class="match-item__live">CANLI</span><span class="match-item__time">18'</span></div></div>
              <div class="match-item" data-fake><div class="match-item__teams">🇦🇷 ARG vs MEX 🇲🇽</div><div class="match-item__meta"><span class="match-item__time" style="color:var(--text-secondary)">21:00</span></div></div>
              <div class="match-item" data-fake><div class="match-item__teams">🇫🇷 FRA vs AUS 🇦🇺</div><div class="match-item__meta"><span class="match-item__time" style="color:var(--text-secondary)">00:00</span></div></div>
            </div>
          </div>
        </div>

        <!-- StreamCore v3.8 CDN Routing -->
        <div class="card">
          <div class="card__header">
            <div class="card__title"><span class="card__title-icon">📡</span> Yayın Bilgisi</div>
            <span class="card__badge card__badge--blue">HD</span>
          </div>
          <div class="card__body">
            <div class="status-grid">
              <div class="status-item"><div class="status-item__label">Kaynak</div><div class="status-item__value" style="font-size:0.8rem;color:var(--text-secondary)">CDN Node</div></div>
              <div class="status-item"><div class="status-item__label">Codec</div><div class="status-item__value" style="font-size:0.8rem;color:var(--text-secondary)">H.264/AAC</div></div>
              <div class="status-item"><div class="status-item__label">Çözünürlük</div><div class="status-item__value" style="font-size:0.8rem;color:var(--text-secondary)">1920x1080</div></div>
              <div class="status-item"><div class="status-item__label">FPS</div><div class="status-item__value" style="font-size:0.8rem;color:var(--text-secondary)">60</div></div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ===== AD BANNER ===== -->
    <div style="margin-top:1.5rem;padding:16px 20px;border-radius:var(--radius);background:linear-gradient(135deg,rgba(0,232,123,0.06),rgba(34,211,238,0.06));border:1px solid rgba(0,232,123,0.2);display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:12px">
        <span style="font-size:1.5rem">🔒</span>
        <div>
          <div style="font-weight:700;font-size:0.9rem;color:var(--accent)">GhostVPN Premium - Dünya Kupası Özel!</div>
          <div style="font-size:0.78rem;color:var(--text-muted)">Kesintisiz yayın için ultra hızlı VPN! İlk 3 ay %70 indirimli. Sınırsız bant genişliği.</div>
        </div>
      </div>
      <button class="btn btn--sm" data-fake style="background:var(--accent-glow);color:var(--accent);border-color:rgba(0,232,123,0.3)">Hemen Katıl →</button>
    </div>

    <!-- ===== SPONSORS ===== -->
    <div style="margin-top:1rem;display:flex;gap:1rem;flex-wrap:wrap">
      <div style="flex:1;min-width:200px;padding:14px;border-radius:var(--radius);background:var(--bg-card);border:1px solid var(--border);text-align:center">
        <div style="font-size:1.2rem;margin-bottom:4px">🏦</div>
        <div style="font-size:0.72rem;color:var(--text-muted)">Ana Sponsor</div>
        <div style="font-size:0.85rem;font-weight:700;color:var(--text-secondary)">GlobalPay Finance</div>
      </div>
      <div style="flex:1;min-width:200px;padding:14px;border-radius:var(--radius);background:var(--bg-card);border:1px solid var(--border);text-align:center">
        <div style="font-size:1.2rem;margin-bottom:4px">🎮</div>
        <div style="font-size:0.72rem;color:var(--text-muted)">Teknoloji Sponsoru</div>
        <div style="font-size:0.85rem;font-weight:700;color:var(--text-secondary)">NexGen Gaming</div>
      </div>
      <div style="flex:1;min-width:200px;padding:14px;border-radius:var(--radius);background:var(--bg-card);border:1px solid var(--border);text-align:center">
        <div style="font-size:1.2rem;margin-bottom:4px">🥤</div>
        <div style="font-size:0.72rem;color:var(--text-muted)">Enerji Sponsoru</div>
        <div style="font-size:0.85rem;font-weight:700;color:var(--text-secondary)">TurboEnergy Drink</div>
      </div>
      <div style="flex:1;min-width:200px;padding:14px;border-radius:var(--radius);background:var(--bg-card);border:1px solid var(--border);text-align:center">
        <div style="font-size:1.2rem;margin-bottom:4px">📱</div>
        <div style="font-size:0.72rem;color:var(--text-muted)">Mobil Uygulama</div>
        <div style="font-size:0.85rem;font-weight:700;color:var(--text-secondary)">ABMSports App</div>
      </div>
    </div>

    <!-- ===== BOTTOM INFO TILES ===== -->
    <div class="info-bar">
      <div class="info-tile"><div class="info-tile__icon">📺</div><div class="info-tile__value" data-stat="847">0</div><div class="info-tile__label">Aktif Yayın</div></div>
      <div class="info-tile"><div class="info-tile__icon">👥</div><div class="info-tile__value" data-stat="24580">0</div><div class="info-tile__label">Toplam İzleyici</div></div>
      <div class="info-tile"><div class="info-tile__icon">🌍</div><div class="info-tile__value" data-stat="42">0</div><div class="info-tile__label">CDN Düğümü</div></div>
      <div class="info-tile"><div class="info-tile__icon">⚡</div><div class="info-tile__value" style="color:var(--accent)">99.8%</div><div class="info-tile__label">Uptime</div></div>
    </div>

  </main>

  <!-- ===== STATUS MODAL ===== -->
  <div class="modal-overlay" id="statusModal">
    <div class="modal">
      <div class="modal__title">📊 Sistem Durumu <button class="modal__close" id="modalClose">&times;</button></div>
      <div id="statusBody"><p style="color:var(--text-muted);font-size:0.85rem">Yükleniyor...</p></div>
    </div>
  </div>

  <!-- ===== FIXTURE MODAL ===== -->
  <div class="modal-overlay" id="fixtureModal">
    <div class="modal" style="width:600px;max-height:80vh;display:flex;flex-direction:column">
      <div class="modal__title">📅 Dünya Kupası Fikstürü <button class="modal__close" onclick="document.getElementById('fixtureModal').classList.remove('active')">&times;</button></div>
      <div id="fixtureBody" style="overflow-y:auto;flex:1;max-height:60vh"><p style="color:var(--text-muted);font-size:0.85rem">Yükleniyor...</p></div>
    </div>
  </div>

  <!-- ===== GROUPS MODAL ===== -->
  <div class="modal-overlay" id="groupsModal">
    <div class="modal" style="width:600px;max-height:80vh;display:flex;flex-direction:column">
      <div class="modal__title">🏆 Grup Puan Durumu <button class="modal__close" onclick="document.getElementById('groupsModal').classList.remove('active')">&times;</button></div>
      <div id="groupsBody" style="overflow-y:auto;flex:1;max-height:60vh"><p style="color:var(--text-muted);font-size:0.85rem">Yükleniyor...</p></div>
    </div>
  </div>

  <!-- ===== LOGIN MODAL ===== -->
  <div class="modal-overlay" id="loginModal">
    <div class="modal" style="width:380px">
      <div class="modal__title">👤 Giriş Yap <button class="modal__close" onclick="document.getElementById('loginModal').classList.remove('active')">&times;</button></div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <input type="text" id="loginUser" placeholder="Kullanıcı adı" style="padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-family:inherit;font-size:0.85rem;outline:none">
        <input type="password" id="loginPass" placeholder="Şifre" style="padding:10px 14px;border-radius:8px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-family:inherit;font-size:0.85rem;outline:none">
        <button class="btn btn--accent" id="btnLoginSubmit" style="justify-content:center">Giriş Yap</button>
        <button class="btn" id="btnRegister" style="justify-content:center">Kayıt Ol</button>
        <a href="#" id="btnForgot" style="text-align:center;font-size:0.78rem;color:var(--text-muted)">Şifremi unuttum</a>
        <div id="loginResult" style="font-size:0.8rem;min-height:20px"></div>
      </div>
    </div>
  </div>

  <!-- ===== NOTIFICATIONS MODAL ===== -->
  <div class="modal-overlay" id="notifModal">
    <div class="modal" style="width:440px">
      <div class="modal__title">🔔 Bildirimler <button class="modal__close" onclick="document.getElementById('notifModal').classList.remove('active')">&times;</button></div>
      <div id="notifBody"><p style="color:var(--text-muted);font-size:0.85rem">Yükleniyor...</p></div>
    </div>
  </div>

  <!-- ===== HIGHLIGHTS MODAL ===== -->
  <div class="modal-overlay" id="highlightsModal">
    <div class="modal" style="width:550px;max-height:80vh;display:flex;flex-direction:column">
      <div class="modal__title">🎬 Maç Özetleri <button class="modal__close" onclick="document.getElementById('highlightsModal').classList.remove('active')">&times;</button></div>
      <div id="highlightsBody" style="overflow-y:auto;flex:1;max-height:60vh"><p style="color:var(--text-muted);font-size:0.85rem">Yükleniyor...</p></div>
    </div>
  </div>

  <!-- ===== QUALITY MODAL ===== -->
  <div class="modal-overlay" id="qualityModal">
    <div class="modal" style="width:340px">
      <div class="modal__title">🎬 Kalite Seçimi <button class="modal__close" onclick="document.getElementById('qualityModal').classList.remove('active')">&times;</button></div>
      <div class="server-list">
        <div class="server-item server-item--active" onclick="selectQuality(this,'1080p60')"><span class="server-item__name"><span class="server-item__dot"></span> 1080p 60fps</span><span class="server-item__ping">HD</span></div>
        <div class="server-item" onclick="selectQuality(this,'720p')"><span class="server-item__name"><span class="server-item__dot"></span> 720p</span><span class="server-item__ping">SD</span></div>
        <div class="server-item" onclick="selectQuality(this,'480p')"><span class="server-item__name"><span class="server-item__dot server-item__dot--yellow"></span> 480p</span><span class="server-item__ping">Düşük</span></div>
        <div class="server-item" onclick="selectQuality(this,'360p')"><span class="server-item__name"><span class="server-item__dot server-item__dot--red"></span> 360p</span><span class="server-item__ping">Mobil</span></div>
      </div>
    </div>
  </div>

  <!-- ===== REPORT MODAL ===== -->
  <div class="modal-overlay" id="reportModal">
    <div class="modal" style="width:400px">
      <div class="modal__title">⚠️ Yayın Bildirimi <button class="modal__close" onclick="document.getElementById('reportModal').classList.remove('active')">&times;</button></div>
      <div style="display:flex;flex-direction:column;gap:10px">
        <select id="reportReason" style="padding:10px;border-radius:8px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-family:inherit;font-size:0.85rem">
          <option value="">Sorun seçin...</option>
          <option value="buffering">Yayın donuyor / kasıyor</option>
          <option value="quality">Görüntü kalitesi düşük</option>
          <option value="audio_sync">Ses-görüntü uyumsuz</option>
          <option value="black_screen">Siyah ekran</option>
          <option value="wrong_match">Yanlış maç gösteriliyor</option>
          <option value="other">Diğer</option>
        </select>
        <textarea id="reportDesc" placeholder="Açıklama (isteğe bağlı)..." rows="3" style="padding:10px;border-radius:8px;border:1px solid var(--border);background:var(--bg-secondary);color:var(--text-primary);font-family:inherit;font-size:0.85rem;resize:vertical"></textarea>
        <button class="btn btn--red" id="btnReportSubmit" style="justify-content:center">Gönder</button>
        <div id="reportResult" style="font-size:0.8rem;min-height:20px"></div>
      </div>
    </div>
  </div>

  <!-- ===== SEARCH RESULTS MODAL ===== -->
  <div class="modal-overlay" id="searchModal">
    <div class="modal" style="width:480px">
      <div class="modal__title">🔍 Arama Sonuçları <button class="modal__close" onclick="document.getElementById('searchModal').classList.remove('active')">&times;</button></div>
      <div id="searchBody"><p style="color:var(--text-muted);font-size:0.85rem">Sonuç yok</p></div>
    </div>
  </div>

  <div class="toast-container" id="toastContainer"></div>

  <footer class="footer">
    <p>© 2026 ABMSports TV — FIFA Dünya Kupası canlı yayınları. Tüm hakları saklıdır.</p>
    <p style="margin-top:4px">Altyapı: CloudShield Pro WAF + StreamCore v3.8 | <a href="#" data-fake>Gizlilik</a> · <a href="#" data-fake>Kullanım Şartları</a> · <a href="#" data-fake>API Dokümantasyonu</a></p>
  </footer>

  <script src="js/app.js"></script>
</body>
</html>
