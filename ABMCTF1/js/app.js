/**
 * ABMSports TV - Frontend Application
 */
(function () {
  'use strict';

  // ===== VIDEO PLAYER =====
  var video = document.getElementById('streamVideo');
  if (video) { video.style.width='100%'; video.style.height='100%'; video.style.objectFit='cover'; }

  // ===== SOUND TOGGLE =====
  var btnSound = document.getElementById('btnSound');
  if (btnSound && video) {
    btnSound.addEventListener('click', function(){
      if (video.muted) {
        video.muted = false; video.volume = 0.7;
        btnSound.textContent = '🔊 Ses Kapat';
      } else {
        video.muted = true;
        btnSound.textContent = '🔇 Ses Aç';
      }
    });
  }

  // ===== VIEWER COUNT =====
  var viewerEl = document.getElementById('viewerCount');
  var currentViewers = 3200;
  function updateViewers() {
    currentViewers = Math.max(800, Math.min(9999, currentViewers + Math.floor((Math.random()-0.45)*80)));
    if (viewerEl) viewerEl.textContent = currentViewers.toLocaleString('tr-TR');
  }
  setInterval(updateViewers, 3000);

  // ===== TOAST SYSTEM =====
  var toastContainer = document.getElementById('toastContainer');
  function showToast(icon, message, type) {
    if (!toastContainer) return;
    var t = document.createElement('div');
    t.className = 'toast toast--' + (type||'success');
    t.innerHTML = '<span>'+icon+'</span><span>'+message+'</span>';
    toastContainer.appendChild(t);
    setTimeout(function(){t.style.opacity='0';t.style.transform='translateX(40px)';t.style.transition='all 0.3s ease';setTimeout(function(){t.remove();},300);},3000);
  }

  // ===== HELPER: Open/Close Modals =====
  function openModal(id) { document.getElementById(id).classList.add('active'); }
  function closeModal(id) { document.getElementById(id).classList.remove('active'); }

  // Close modals on overlay click
  document.querySelectorAll('.modal-overlay').forEach(function(ov){
    ov.addEventListener('click', function(e){ if(e.target===ov) ov.classList.remove('active'); });
  });

  // ===== 1. SYSTEM STATUS (Real vulnerability trigger) =====
  var btnStatus = document.getElementById('btnStatus');
  var statusModal = document.getElementById('statusModal');
  var modalClose = document.getElementById('modalClose');
  if (btnStatus) {
    btnStatus.addEventListener('click', async function(){
      try {
        var res = await fetch('status.php?t='+Date.now());
        var data = await res.json();
        var h = '<div class="status-grid">';
        h += tile('CPU',data.cpu_load,'green')+tile('RAM',data.memory_usage,'orange');
        h += tile('İzleyici',data.active_viewers.toLocaleString(),'green')+tile('Bant Genişliği',data.bandwidth_mbps+' Mbps','green');
        h += tile('Gecikme',data.latency_ms+' ms',data.latency_ms>50?'orange':'green')+tile('Önbellek',data.cache_hit_rate,'green');
        h += tile('Düğüm',data.active_node,'green')+tile('Uptime',data.uptime,'green');
        h += '</div>';
        var cpuVal=parseInt(data.cpu_load);
        h += '<div style="margin-top:14px"><div style="display:flex;justify-content:space-between;margin-bottom:4px"><span style="font-size:0.72rem;color:var(--text-muted)">CPU KULLANIMI</span><span style="font-size:0.72rem;color:var(--accent);font-family:JetBrains Mono,monospace">'+data.cpu_load+'</span></div><div class="status-bar__track"><div class="status-bar__fill" style="width:'+cpuVal+'%"></div></div></div>';
        document.getElementById('statusBody').innerHTML = h;
        openModal('statusModal');
        showToast('✅','Sistem durumu güncellendi','success');
      } catch(e){ showToast('❌','Sistem durumu alınamadı','error'); }
    });
  }
  if(modalClose) modalClose.addEventListener('click',function(){closeModal('statusModal');});

  function tile(l,v,c){return '<div class="status-item"><div class="status-item__label">'+l+'</div><div class="status-item__value status-item__value--'+c+'">'+v+'</div></div>';}

  // ===== 2. LIVE CHAT (Client-side, resets on refresh) =====
  var chatBox = document.getElementById('chatMessages');
  var chatInput = document.getElementById('chatInput');
  var btnChatSend = document.getElementById('btnChatSend');
  var chatMessages = [];
  var chatOnlineCount = 2800 + Math.floor(Math.random()*1500);

  var chatPool = [
    {u:'GolKrali_34',m:'GOOOLLLL! 🎉🇹🇷'},{u:'FutbolDelisi',m:'Ne maç be! Türkiye çok iyi oynuyor'},
    {u:'AyYildiz2026',m:'Hadi Türkiyem! 🇹🇷🇹🇷🇹🇷'},
    {u:'Admin_ABM',m:'Yayın kalitesi düştüyse sunucu değiştirin, altyapı şu an %90 yükte!'},
    {u:'SporAsi',m:'Gürcistan da fena değil aslında, Kvaratskhelia çok tehlikeli adam dikkat etmek lazım.'},
    {u:'MaçKolik',m:'Bu penaltıydı ya! Hakem resmen uyuyor, VAR\'a bile gitmedi.'},
    {u:'CimBom_1905',m:'GS\'liler fark yaratıyor 💛❤️ Barış Alper resmen traktör gibi eziyor defansı.'},
    {u:'TribunFanatik',m:'Stream çok kasıyor bende, F5 atıyorum yine aynı.'},
    {u:'GolAvcisi_07',m:'İkinci yarı daha iyi olacak, 1-0 tehlikeli skor beyler acil 2\'yi bulmamız lazım.'},
    {u:'Admin_ABM',m:'⚠️ Avrupa #1 yoğun, #2 ye geçin. Küfür edenler süresiz banlanacaktır!'},
    {u:'Fenerbull',m:'GOL GELİYOR HİSSEDİYORUM 🔥 Arda Güler yapacak şovunu birazdan.'},
    {u:'SesliTribun',m:'Türkiye Türkiye! 🎵 Kırmızı beyaz şampiyon Türkiye!'},
    {u:'FutbolTaktik',m:'Orta sahada çok boşluk bırakıyoruz, Gürcistan kontra ataklarda hızlı çıkıyor.'},
    {u:'KaanBey',m:'Beyler maç kaç kaç ben yeni girdim yayına?'},
    {u:'Esra_TR',m:'1-0 öndeyiz ama ömrümden ömür gitti yemin ederim.'},
    {u:'Dark_Hacker',m:'Kaçak yayında bile 4K izliyoruz admin helal olsun sana altyapı efsane.'},
    {u:'VatanMillet',m:'İkinci gol gelmezse bu Gürcüler bize ecel teri döktürür, çok saldırıyorlar.'},
    {u:'Kartal_Ruhu',m:'Semih nerede abi alsın artık şu çocuğu oyuna da gol görelim!'},
    {u:'YayıncıMod',m:'Chat yavaş mod aktiftir. Lütfen spam yapmayınız.'},
    {u:'Crazy_Boy99',m:'Ahhhh direkten döndü! Nasıl girmez o ya nasıl!'},
    {u:'ZeynepKSK',m:'Kalecimiz bugün efsane oynuyor, o son şutu nasıl çıkardı öyle maşallah.'},
    {u:'Batu_1907',m:'Fenerbahçe lobisi olmasa bu takım iş yapamaz asdadasd'},
    {u:'AslanParcasi',m:'Kulüpçülük yapmayın lan milli maç burası! Hepimiz Türkiyeyiz 🇹🇷'},
    {u:'TaktikEksperi',m:'Defansın arasına atılan uzun toplarda çok aksıyoruz, hoca bir hamle yapmalı.'},
    {u:'Ali_Vefa',m:'2-0 olacak yazın kenara, Kenan Yıldız fişi çekecek.'},
    {u:'YorgunSavasci',m:'Yayın dondu dondu donduuuu!'},
    {u:'Korsan_Avcisi',m:'Bende donma falan yok sizin netiniz patates beyler.'},
    {u:'Emre_Kaya',m:'Şut atın artık ceza sahasına girmeden şut çekin şu kaleciye!'},
    {u:'MilliAsik',m:'Hadi aslanlarım, hadi koçlarım! Bütün Avrupa bizi izliyor şu an.'},
    {u:'Gurbetci_06',m:'Almanya\'dan selamlar, burada herkes sokaklarda maçı izliyor!'},
    {u:'DefansBakani',m:'Merih bugün duvar gibi yemin ederim adam geçirmiyor.'},
    {u:'Ahmet_55',m:'Ayağınızda tutun şu topu şişirip durmayın ileriye!'},
    {u:'Streamer_Kral',m:'Yayın 10 numara 5 yıldız, donanlar VPN kapatsın.'},
    {u:'Admin_ABM',m:'Sohbette siyaset ve kulüp tartışması yapan 3 kişi uzaklaştırıldı.'},
    {u:'Fenerli_Taha',m:'Hakem tam bir fiyasko, bütün takdir haklarını Gürcistan\'dan yana kullanıyor.'},
    {u:'Goztepeli_Efe',m:'İzmir\'den sevgiler, hadi be çocuklar atın bir tane daha rahatlayalım.'},
    {u:'SiyahBeyazAsk',m:'Orta sahada İsmail Yüksek eksikliği çok belli oluyor, top kesemiyoruz.'},
    {u:'RastgeleBiri',m:'Vurun ulan vurun acımayın!'},
    {u:'Kaan_Reis',m:'Kvaratskhelia\'yı ikili sıkıştırmaya almaları lazım, adam tek başına sağ kanadı yıktı.'},
    {u:'Cem_Yilmaz_Fan',m:'Bir cisim yaklaşıyor... 2. gol geliyor hissediyorum!'},
    {u:'MertCan_01',m:'Yusuf\'u da alsaydı kadroya varya şimdi şov yapardı.'},
    {u:'Ayse_22',m:'Ofsayt yok muydu o pozisyonda? Kalbim duracaktı az kalsın.'},
    {u:'TurkOgluTurk',m:'Hadi aslanlarım gösterin Türk\'ün gücünü 🐺🇹🇷'},
    {u:'Yusuf_GS',m:'Barış Alper yoruldu artık değiştirmesi lazım hocanın.'},
    {u:'Veli_Dayi',m:'Gençler maç bitmek üzere mi kaçıncı dakika?'},
    {u:'Batu_TR',m:'Daha var dayı sakin ol 75. dakika falan.'},
    {u:'Cyber_Wolf',m:'Sistemin anlık durumunu merak edenler F12 console\'a bakın :D Şaka maç izleyin.'},
    {u:'Hakan_Abi',m:'Bravo kaleci, bravo oğlum! Çizgiden çıkardı resmen.'},
    {u:'Reis_Sedat',m:'Hakem bitir hocaaaaa bitirrrrrr'},
    {u:'Futbol_Kurdu',m:'Adamlar risk almaya başladı, arkada çok boşluk. Tam Arda\'lık maç oldu.'},
    {u:'Caner_1907',m:'Ayağa pas yapın abi dikmeyin şu topu!'},
    {u:'Buse_Ceylan',m:'Ya gol olsun artık lütfen stres oldum burada.'},
    {u:'MilliDava',m:'2. gol gelmezse kriz geçiricem yemin ederim. 🇹🇷'},
    {u:'Admin_ABM',m:'Arka planda sunucu kapasitesi artırılıyor, 5 saniyelik bir kesinti olabilir.'},
    {u:'Kara_Kartal_1903',m:'Mert kurtarıyor valla Beşiktaşın aslanı!'},
    {u:'OyunKolu',m:'Bu Gürcistan fifada bu kadar iyi değil gerçekte nasıl oynuyorlar böyle'},
    {u:'Selim_44',m:'Faul verdi inanılmaz! Dokunmadı bile adam!'},
    {u:'Gorkem_Tr',m:'Hücum hoca hücum defansa çekilmek intihardır bu dakikada.'},
    {u:'Yasar_Kemal',m:'O top ağlarla buluşacak başka yolu yok!'},
    {u:'Enes_Batur_Fan',m:'Chat çok hızlı okuyamıyorum'},
    {u:'Samet_55',m:'Kerem Aktürkoğlu füzeyi yollayacak birazdan bekleyin.'},
    {u:'Hasan_Huseyin',m:'Müthiş paslaştık ama son vuruş eksik be usta.'},
    {u:'Efe_Kaan',m:'VAR\'a gidiyor mu hakem? El var gibiydi orada.'},
    {u:'Deli_Kadir',m:'Yok lan ne VAR\'ı devam ettirdi kör hakem.'},
    {u:'Kirmizi_Beyaz',m:'Kanları kırmızı yüzleri ak, hadi bizim çocuklar! 🇹🇷'},
    {u:'Ali_Bozkurt',m:'Kontra atağa çok yavaş çıkıyoruz, biraz hızlı beyler.'},
    {u:'Vip_Kullanici',m:'Yayın kalitesi 1080p cillop gibi, site iş yapıyor.'},
    {u:'Siber_Mete',m:'Sunucunun pingi fırladı Gürcüler bot mu basıyor naptı :)'},
    {u:'Emine_K',m:'Hakan Çalhanoğlu çok klas oynuyor maşallah.'},
    {u:'Taha_FB',m:'Direkten döndü offfffffffff'},
    {u:'Berkay_09',m:'Nasıl girmez o top çıldıracağım!'},
    {u:'Uzman_Taktikci',m:'Kenan çok yoruldu defansa yardıma gelemiyor, sol taraf çöktü.'},
    {u:'Admin_ABM',m:'Lütfen izleme sayfasına F5 atmayın, sunucuya gereksiz yük biniyor!'},
    {u:'Yalniz_Kurt',m:'Son 10 dakika, dayanın beyler!'},
    {u:'Osman_Dayi',m:'Gol yedik mi gençler benim yayın geriden geliyor?'},
    {u:'Burak_Y',m:'Hayır dayı yemedik 1-0 devam ama ecel terleri döküyoruz.'},
    {u:'Cagatay_TR',m:'Gol geliyor 2 oldu 2!!'},
    {u:'Merve_Nur',m:'Trollüyor inanmayın gol falan yok hala 1-0'},
    {u:'Asim_Abi',m:'Kalpten götüreceksiniz adamı yalan söylemeyin lan.'},
    {u:'Fatih_Reis',m:'Topu dikin ileri vakit geçirin artık risk almanın alemi yok.'},
    {u:'Kaan_BJK',m:'Orkun bugün beklentinin çok altında kaldı, top eziyor sürekli.'},
    {u:'TurkGucu',m:'Avrupa Fatihi Türkiye! Eze eze yeneceğiz!'},
    {u:'Musti_98',m:'Sakatlık var galiba oyun durdu.'},
    {u:'Batu_1907',m:'Yok ya numaradan yatıyor vakit geçirmek için, klas hareketler bunlar.'},
    {u:'GolKrali_34',m:'Hocam bitir artık 90 dakika oldu yeterrr!'},
    {u:'Tribun_Genc',m:'Uzatma kaç dakika verdi?'},
    {u:'Siyah_Beyaz_1903',m:'+5 verdi bitemez bu maç ya.'},
    {u:'Yasin_Kaya',m:'Allahım sen koru yemin ederim heyecandan ellerim titriyor.'},
    {u:'Admin_ABM',m:'Son dakikalar, yayında kopma olmaması için chat yavaşlatıldı.'},
    {u:'CimBomlu_Kemal',m:'Taç bizim kullanın da bitsin şu iş.'},
    {u:'Fenerbull',m:'Kontraya çıkıyoruz boş kaleeeeeee'},
    {u:'Hakan_Abi',m:'Vurrrrrr golll olsun o artıkkk'},
    {u:'Milli_Ruh',m:'At onu Arda at onu canım benim!'},
    {u:'Crazy_Boy99',m:'GOLLLLLLLLLLL BE GOLLLLLLLLL'},
    {u:'ZeynepKSK',m:'GOOOOOOOOOOOOOOOOOOLLLLLLL 2-0 OLDUUUUU'},
    {u:'FutbolDelisi',m:'ALLAHIM GOOOOOOL FİŞİ ÇEKTİKKKKKK'},
    {u:'Kartal_Ruhu',m:'HELAL OLSUN BE SİZE BOŞ KALEYE YAZDIIIII'},
    {u:'Gurbetci_06',m:'ALMANYA YIKILIYOR ŞU AN GOOOOOLL'},
    {u:'Admin_ABM',m:'GOOOOOOOOOOLLLLL 🇹🇷🇹🇷🇹🇷🇹🇷'},
    {u:'GolAvcisi_07',m:'İşte bu be işte bu bitti bu maç!'},
    {u:'SporAsi',m:'Muazzam bir depar muazzam bir gol helal olsun.'}
  ];

  var usedIndices = [];

  function getTimeStr(){ var d=new Date(); return ('0'+d.getHours()).slice(-2)+':'+('0'+d.getMinutes()).slice(-2)+':'+('0'+d.getSeconds()).slice(-2); }

  function addChatMessage(user, msg, isUser) {
    if(!chatBox) return;
    chatMessages.push({u:user,m:msg,t:getTimeStr(),me:isUser||false});
    if(chatMessages.length > 50) chatMessages.shift();
    renderChat();
  }

  function renderChat() {
    if(!chatBox) return;
    chatBox.innerHTML = '';
    chatMessages.forEach(function(cm){
      var div = document.createElement('div');
      var nameColor = cm.me ? 'color:var(--cyan)' : 'color:var(--accent)';
      var badge = cm.me ? ' <span style="background:rgba(34,211,238,0.15);color:var(--cyan);padding:1px 5px;border-radius:3px;font-size:0.6rem;font-weight:700">SEN</span>' : '';
      div.innerHTML = '<span style="'+nameColor+';font-weight:600">'+cm.u+'</span>'+badge+' <span style="color:var(--text-muted);font-size:0.7rem">'+cm.t+'</span><br><span style="color:var(--text-secondary)">'+cm.m+'</span>';
      chatBox.appendChild(div);
    });
    chatBox.scrollTop = chatBox.scrollHeight;
    var onlineEl = document.getElementById('chatOnline');
    if(onlineEl) {
      chatOnlineCount += Math.floor((Math.random()-0.48)*30);
      chatOnlineCount = Math.max(1500, Math.min(5000, chatOnlineCount));
      onlineEl.textContent = chatOnlineCount.toLocaleString('tr-TR')+' Çevrimiçi';
    }
  }

  function addRandomChat() {
    if(usedIndices.length >= chatPool.length) usedIndices = [];
    var idx;
    do { idx = Math.floor(Math.random()*chatPool.length); } while(usedIndices.indexOf(idx) !== -1);
    usedIndices.push(idx);
    var pick = chatPool[idx];
    addChatMessage(pick.u, pick.m, false);
  }

  // Start with 6 random messages
  for(var ci=0; ci<6; ci++) addRandomChat();
  // Add a new random message every 5 seconds
  setInterval(addRandomChat, 5000);

  if(btnChatSend && chatInput){
    function sendChat(){
      var msg = chatInput.value.trim();
      if(!msg) return;
      addChatMessage('Sen', msg, true);
      chatInput.value = '';
    }
    btnChatSend.addEventListener('click', sendChat);
    chatInput.addEventListener('keydown', function(e){ if(e.key==='Enter') sendChat(); });
  }

  // ===== 3. FIXTURE =====
  var navFixture = document.getElementById('navFixture');
  if(navFixture){
    navFixture.addEventListener('click',function(e){
      e.preventDefault();
      fetch('fixture.php?group=ALL&t='+Date.now()).then(function(r){return r.json();}).then(function(data){
        var h='';
        var fixtures = data.fixtures;
        for(var g in fixtures){
          h+='<div style="margin-bottom:12px"><div style="font-weight:700;font-size:0.85rem;margin-bottom:6px;color:var(--accent)">Grup '+g+'</div>';
          fixtures[g].forEach(function(m){
            var statusHtml = m.status==='live'?'<span style="background:var(--red);color:#fff;padding:1px 6px;border-radius:3px;font-size:0.65rem;font-weight:700">CANLI '+m.minute+"'"+'</span>':m.status==='finished'?'<span style="color:var(--text-muted);font-size:0.75rem">Bitti</span>':'<span style="color:var(--text-secondary);font-size:0.75rem">'+m.time+'</span>';
            h+='<div class="match-item" style="cursor:default"><div class="match-item__teams">'+m.home_flag+' '+m.home+' '+m.score+' '+m.away+' '+m.away_flag+'</div><div class="match-item__meta">'+statusHtml+'</div></div>';
          });
          h+='</div>';
        }
        document.getElementById('fixtureBody').innerHTML=h;
        openModal('fixtureModal');
      }).catch(function(){showToast('❌','Fikstür yüklenemedi','error');});
    });
  }

  // ===== 4. GROUPS / STANDINGS =====
  var navGroups = document.getElementById('navGroups');
  var navStandings = document.getElementById('navStandings');
  function loadGroups(){
    fetch('groups.php?group=ALL&t='+Date.now()).then(function(r){return r.json();}).then(function(data){
      var h='';
      var standings=data.standings;
      for(var g in standings){
        var grp=standings[g];
        h+='<div style="margin-bottom:16px"><div style="font-weight:700;font-size:0.85rem;margin-bottom:8px;color:var(--accent)">'+grp.name+'</div>';
        h+='<table style="width:100%;font-size:0.75rem;border-collapse:collapse"><thead><tr style="color:var(--text-muted)"><th style="text-align:left;padding:4px 6px">#</th><th style="text-align:left;padding:4px 6px">Takım</th><th style="padding:4px 6px">O</th><th style="padding:4px 6px">G</th><th style="padding:4px 6px">B</th><th style="padding:4px 6px">M</th><th style="padding:4px 6px">A</th><th style="padding:4px 6px;color:var(--accent);font-weight:700">P</th></tr></thead><tbody>';
        grp.teams.forEach(function(t){
          var rowColor = t.pos<=2?'color:var(--text-primary)':'color:var(--text-muted)';
          h+='<tr style="'+rowColor+';border-top:1px solid var(--border)"><td style="padding:6px">'+t.pos+'</td><td style="padding:6px;font-weight:600">'+t.flag+' '+t.team+'</td><td style="padding:6px;text-align:center">'+t.played+'</td><td style="padding:6px;text-align:center">'+t.won+'</td><td style="padding:6px;text-align:center">'+t.drawn+'</td><td style="padding:6px;text-align:center">'+t.lost+'</td><td style="padding:6px;text-align:center">'+t.gd+'</td><td style="padding:6px;text-align:center;font-weight:700;color:var(--accent)">'+t.points+'</td></tr>';
        });
        h+='</tbody></table></div>';
      }
      document.getElementById('groupsBody').innerHTML=h;
      openModal('groupsModal');
    }).catch(function(){showToast('❌','Grup bilgileri yüklenemedi','error');});
  }
  if(navGroups) navGroups.addEventListener('click',function(e){e.preventDefault();loadGroups();});
  if(navStandings) navStandings.addEventListener('click',function(e){e.preventDefault();loadGroups();});

  // ===== 5. LOGIN =====
  var btnLogin = document.getElementById('btnLogin');
  if(btnLogin) btnLogin.addEventListener('click',function(){openModal('loginModal');});

  var btnLoginSubmit = document.getElementById('btnLoginSubmit');
  if(btnLoginSubmit){
    btnLoginSubmit.addEventListener('click',function(){
      var u=document.getElementById('loginUser').value;
      var p=document.getElementById('loginPass').value;
      var resultEl=document.getElementById('loginResult');
      resultEl.innerHTML='<span style="color:var(--text-muted)">Giriş yapılıyor...</span>';
      fetch('auth.php?action=login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({username:u,password:p})}).then(function(r){return r.json();}).then(function(data){
        resultEl.innerHTML='<span style="color:var(--red)">❌ '+(data.message||'Giriş başarısız')+'</span>';
        if(data.attempts_remaining) resultEl.innerHTML+='<br><span style="color:var(--text-muted);font-size:0.72rem">Kalan deneme: '+data.attempts_remaining+'</span>';
      }).catch(function(){resultEl.innerHTML='<span style="color:var(--red)">Bağlantı hatası</span>';});
    });
  }

  var btnRegister = document.getElementById('btnRegister');
  if(btnRegister){
    btnRegister.addEventListener('click',function(){
      var resultEl=document.getElementById('loginResult');
      fetch('auth.php?action=register',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({})}).then(function(r){return r.json();}).then(function(data){
        resultEl.innerHTML='<span style="color:var(--orange)">⚠️ '+(data.message||'Kayıtlar kapalı')+'</span>';
      }).catch(function(){});
    });
  }

  var btnForgot = document.getElementById('btnForgot');
  if(btnForgot){
    btnForgot.addEventListener('click',function(e){
      e.preventDefault();
      fetch('auth.php?action=reset').then(function(r){return r.json();}).then(function(data){
        document.getElementById('loginResult').innerHTML='<span style="color:var(--orange)">⚠️ '+data.message+'</span>';
      }).catch(function(){});
    });
  }

  // ===== 6. NOTIFICATIONS =====
  var btnNotif = document.getElementById('btnNotif');
  if(btnNotif){
    btnNotif.addEventListener('click',function(){
      fetch('notifications.php?t='+Date.now()).then(function(r){return r.json();}).then(function(data){
        var h='<div style="display:flex;flex-direction:column;gap:8px">';
        data.notifications.forEach(function(n){
          var bg = n.read?'var(--bg-secondary)':'var(--accent-glow)';
          var border = n.read?'transparent':'rgba(0,232,123,0.2)';
          h+='<div style="padding:10px 12px;border-radius:8px;background:'+bg+';border:1px solid '+border+';font-size:0.82rem"><div style="font-weight:600">'+n.title+'</div><div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px">'+n.time+'</div></div>';
        });
        h+='</div>';
        document.getElementById('notifBody').innerHTML=h;
        openModal('notifModal');
        document.getElementById('notifBadge').style.display='none';
      }).catch(function(){});
    });
  }

  // ===== 7. QUALITY SELECTOR =====
  var btnQuality = document.getElementById('btnQuality');
  if(btnQuality) btnQuality.addEventListener('click',function(){openModal('qualityModal');});

  window.selectQuality = function(el,q){
    document.querySelectorAll('#qualityModal .server-item').forEach(function(s){s.classList.remove('server-item--active');});
    el.classList.add('server-item--active');
    document.querySelector('.player__quality-tag').textContent = q;
    showToast('🎬','Kalite değiştirildi: '+q,'success');
    setTimeout(function(){closeModal('qualityModal');},500);
  };

  // ===== 8. REPORT =====
  var btnReport = document.getElementById('btnReport');
  if(btnReport) btnReport.addEventListener('click',function(){openModal('reportModal');});

  var btnReportSubmit = document.getElementById('btnReportSubmit');
  if(btnReportSubmit){
    btnReportSubmit.addEventListener('click',function(){
      var reason = document.getElementById('reportReason').value;
      var desc = document.getElementById('reportDesc').value;
      var resultEl = document.getElementById('reportResult');
      if(!reason){resultEl.innerHTML='<span style="color:var(--red)">Lütfen bir sorun seçin</span>';return;}
      resultEl.innerHTML='<span style="color:var(--text-muted)">Gönderiliyor...</span>';
      fetch('report.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({channel:'tur-geo-live',reason:reason,description:desc})}).then(function(r){return r.json();}).then(function(data){
        resultEl.innerHTML='<span style="color:var(--accent)">✅ '+data.message+' (Bilet: '+data.ticket_id+')</span>';
        showToast('✅','Bildirim gönderildi','success');
      }).catch(function(){resultEl.innerHTML='<span style="color:var(--red)">Gönderilemedi</span>';});
    });
  }

  // ===== 9. SEARCH =====
  var searchInput = document.getElementById('searchInput');
  var searchTimer = null;
  if(searchInput){
    searchInput.addEventListener('keydown',function(e){
      if(e.key==='Enter'){
        var q=searchInput.value.trim();
        if(!q)return;
        fetch('search.php?q='+encodeURIComponent(q)+'&t='+Date.now()).then(function(r){return r.json();}).then(function(data){
          var h='';
          if(data.count===0){h='<p style="color:var(--text-muted);font-size:0.85rem">Sonuç bulunamadı: "'+data.query+'"</p>';}
          else{
            h='<p style="color:var(--text-muted);font-size:0.78rem;margin-bottom:10px">'+data.count+' sonuç bulundu</p>';
            data.results.forEach(function(r){
              var icon = r.type==='team'?'🏳️':r.type==='match'?'⚽':'📺';
              var extra = r.type==='team'?'Grup '+r.group+' · FIFA #'+r.rank:r.type==='match'?(r.status==='live'?'CANLI '+r.score:''):r.viewers+' izleyici';
              h+='<div class="match-item" style="cursor:default;margin-bottom:6px"><div class="match-item__teams">'+icon+' '+(r.flag||'')+' '+r.name+'</div><div class="match-item__meta"><span style="font-size:0.72rem;color:var(--text-muted)">'+extra+'</span></div></div>';
            });
          }
          document.getElementById('searchBody').innerHTML=h;
          openModal('searchModal');
        }).catch(function(){});
      }
    });
  }

  // ===== 10. LIVE MATCHES NAV =====
  var navLive = document.getElementById('navLive');
  if(navLive){
    navLive.addEventListener('click',function(e){
      e.preventDefault();
      showToast('📺','Zaten canlı maç sayfasındasınız','success');
      window.scrollTo({top:0,behavior:'smooth'});
    });
  }

  // ===== 11. MATCH HIGHLIGHTS =====
  var navHighlights = document.getElementById('navHighlights');
  var highlightMatches = [
    {teams:'🇶🇦 Katar vs Ekvador 🇪🇨', score:'0 - 2', file:'QATvsECU_highlights.mp4', group:'A'},
    {teams:'🏴󠁧󠁢󠁥󠁮󠁧󠁿 İngiltere vs İran 🇮🇷', score:'6 - 2', file:'ENGvsIRN_highlights.mp4', group:'B'},
    {teams:'🇦🇷 Arjantin vs S.Arabistan 🇸🇦', score:'1 - 2', file:'ARGvsSAU_highlights.mp4', group:'C'},
    {teams:'🇸🇳 Senegal vs Hollanda 🇳🇱', score:'0 - 2', file:'SENvsNED_highlights.mp4', group:'A'},
    {teams:'🇺🇸 ABD vs Galler 🏴󠁧󠁢󠁷󠁬󠁳󠁿', score:'1 - 1', file:'USAvsWAL_highlights.mp4', group:'B'},
    {teams:'🇨🇭 İsviçre vs Kamerun 🇨🇲', score:'1 - 0', file:'SUIsCAM_highlights.mp4', group:'G'},
    {teams:'🇩🇰 Danimarka vs Tunus 🇹🇳', score:'0 - 0', file:'DENvsTUN_highlights.mp4', group:'H'},
  ];

  if(navHighlights){
    navHighlights.addEventListener('click', function(e){
      e.preventDefault();
      var h = '';
      highlightMatches.forEach(function(m, i){
        h += '<div class="match-item" style="cursor:pointer" id="hl_'+i+'">';
        h += '<div class="match-item__teams">'+m.teams+'</div>';
        h += '<div class="match-item__meta"><span style="font-size:0.75rem;color:var(--text-muted)">Grup '+m.group+' · '+m.score+'</span> <span style="font-size:0.65rem;padding:2px 6px;border-radius:3px;background:rgba(34,211,238,0.1);color:var(--cyan)">▶ Özet</span></div>';
        h += '</div>';
        h += '<div id="hlStatus_'+i+'" style="display:none;padding:6px 12px;margin-bottom:8px;border-radius:6px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);font-size:0.75rem;color:var(--red)"></div>';
      });
      document.getElementById('highlightsBody').innerHTML = h;
      openModal('highlightsModal');

      // Attach click handlers — each one fires a real request with file= parameter
      highlightMatches.forEach(function(m, i){
        document.getElementById('hl_'+i).addEventListener('click', function(){
          var statusEl = document.getElementById('hlStatus_'+i);
          statusEl.style.display = 'block';
          statusEl.innerHTML = '<span style="color:var(--text-muted)">⏳ Özet yükleniyor...</span>';
          // This request fires: stream.php?source=internal-cdn&file=XXX.mp4
          // Visible in Network tab — reveals the "file" parameter!
          fetch('stream.php?source=internal-cdn&file='+m.file).then(function(r){
            if(!r.ok) throw new Error(r.status);
            return r.blob();
          }).then(function(){
            statusEl.innerHTML = '<span style="color:var(--accent)">✅ Özet yüklendi</span>';
          }).catch(function(){
            statusEl.innerHTML = '<div style="font-family:monospace;font-size:0.7rem;line-height:1.5">'
              + '<span style="color:var(--red);font-weight:700">Fatal error:</span> <span style="color:var(--text-secondary)">Uncaught PDOException: SQLSTATE[HY000] [2002] Connection refused in /var/www/streamcore/lib/MediaResolver.php:142</span><br>'
              + '<span style="color:var(--text-muted)">Stack trace:</span><br>'
              + '<span style="color:var(--text-muted)">#0 /var/www/streamcore/lib/MediaResolver.php(142): PDO->__construct(\'mysql:host=db.stream...\')</span><br>'
              + '<span style="color:var(--text-muted)">#1 /var/www/streamcore/lib/CacheLayer.php(89): MediaResolver->fetchFromStorage(\'<span style="color:var(--orange)">'+m.file+'</span>\')</span><br>'
              + '<span style="color:var(--text-muted)">#2 /var/www/html/stream.php(204): CacheLayer->resolve(source=\'internal-cdn\', file=\'<span style="color:var(--orange)">'+m.file+'</span>\')</span><br>'
              + '<span style="color:var(--text-muted)">#3 {main}</span><br>'
              + '<span style="color:var(--red)">  thrown in /var/www/streamcore/lib/MediaResolver.php on line 142</span>'
              + '</div>';
          });
        });
      });
    });
  }

  // ===== SERVER SWITCH =====
  document.querySelectorAll('.sidebar .server-item').forEach(function(item){
    item.addEventListener('click',function(){
      item.parentElement.querySelectorAll('.server-item').forEach(function(s){s.classList.remove('server-item--active');});
      item.classList.add('server-item--active');
      var src = item.dataset.source;
      if (src && video) {
        var newUrl = 'stream.php?source=' + src + '&ch=1&stream=live';
        video.querySelector('source').setAttribute('src', newUrl);
        video.load();
        video.play();
      }
      showToast('🔄','Sunucu değiştirildi: '+item.dataset.name,'success');
    });
  });

  // ===== FAKE BUTTON HANDLER =====
  document.querySelectorAll('[data-fake]').forEach(function(btn){
    btn.addEventListener('click',function(e){e.preventDefault();showToast('⚠️','Bu özellik şu an kullanılamıyor','error');});
  });

  // ===== BOTTOM STATS ANIMATION =====
  document.querySelectorAll('[data-stat]').forEach(function(el){
    var target=parseInt(el.dataset.stat),current=0,step=Math.ceil(target/40);
    var iv=setInterval(function(){current=Math.min(current+step,target);el.textContent=current.toLocaleString('tr-TR');if(current>=target)clearInterval(iv);},30);
  });

  // ===== MATCH TIME TICKER =====
  var matchTimeEl=document.getElementById('matchTime');
  if(matchTimeEl){var matchMin=67;setInterval(function(){matchMin++;if(matchMin>90)matchMin=90;matchTimeEl.textContent=matchMin+"'";},60000);}

  // ===== 12. OTHER LIVE SCORES & GOAL SOUND =====
  var liveMatches = [
    {h:'FRA 🇫🇷', a:'ITA 🇮🇹', sh:0, sa:0, time:45, active:true},
    {h:'ESP 🇪🇸', a:'POR 🇵🇹', sh:1, sa:1, time:62, active:true},
    {h:'GER 🇩🇪', a:'NED 🇳🇱', sh:2, sa:0, time:78, active:true},
    {h:'BRA 🇧🇷', a:'ARG 🇦🇷', sh:0, sa:1, time:33, active:true},
    {h:'ENG 🏴󠁧󠁢󠁥󠁮󠁧󠁿', a:'BEL 🇧🇪', sh:0, sa:0, time:12, active:true},
    {h:'CRO 🇭🇷', a:'MAR 🇲🇦', sh:1, sa:2, time:85, active:true}
  ];

  var liveScoresGrid = document.getElementById('liveScoresGrid');
  var btnGoalSound = document.getElementById('btnGoalSound');
  var goalSoundEnabled = true;

  if (btnGoalSound) {
    btnGoalSound.addEventListener('click', function() {
      goalSoundEnabled = !goalSoundEnabled;
      if (goalSoundEnabled) {
        btnGoalSound.innerHTML = '🔊 Gol Sesi Açık';
        btnGoalSound.style.background = 'rgba(34, 197, 94, 0.1)';
        btnGoalSound.style.color = 'var(--green)';
        btnGoalSound.style.borderColor = 'rgba(34, 197, 94, 0.2)';
      } else {
        btnGoalSound.innerHTML = '🔕 Gol Sesi Kapalı';
        btnGoalSound.style.background = 'rgba(239, 68, 68, 0.1)';
        btnGoalSound.style.color = 'var(--red)';
        btnGoalSound.style.borderColor = 'rgba(239, 68, 68, 0.2)';
      }
    });
  }

  var audioCtx = null;
  function playGoalDing() {
    if(!goalSoundEnabled) return;
    try {
      if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      if(audioCtx.state === 'suspended') audioCtx.resume();
      var osc = audioCtx.createOscillator();
      var gain = audioCtx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(880, audioCtx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.1);
      gain.gain.setValueAtTime(0, audioCtx.currentTime);
      gain.gain.linearRampToValueAtTime(0.4, audioCtx.currentTime + 0.05);
      gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.5);
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.5);
    } catch(e) {}
  }

  // Inject bounce animation CSS
  var style = document.createElement('style');
  style.innerHTML = '@keyframes goalBounce { 0%, 100% {transform: translateY(0);} 50% {transform: translateY(-4px);} }';
  document.head.appendChild(style);

  function renderLiveScores() {
    if(!liveScoresGrid) return;
    var html = '';
    liveMatches.forEach(function(m) {
      var goalH = m.showGoalH ? '<span style="animation: goalBounce 0.4s infinite; display:inline-block">⚽</span> ' : '';
      var goalA = m.showGoalA ? ' <span style="animation: goalBounce 0.4s infinite; display:inline-block">⚽</span>' : '';
      var bg = (m.showGoalH || m.showGoalA) ? 'background: rgba(34, 197, 94, 0.15); border-color: rgba(34, 197, 94, 0.5);' : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05);';

      html += '<div style="padding: 12px; border-radius: 8px; border: 1px solid; transition: all 0.3s; ' + bg + '">';
      html += '<div style="display:flex; justify-content:space-between; margin-bottom: 8px; font-size: 0.75rem; color:var(--text-muted);">';
      html += '<span><span class="live-indicator" style="width:6px;height:6px;margin-right:4px"></span> ' + m.time + '\'</span>';
      html += (m.showGoalH || m.showGoalA) ? '<span style="color:var(--green);font-weight:bold;animation:flash 1s infinite">GOL!</span>' : '';
      html += '</div>';
      html += '<div style="display:flex; justify-content:space-between; font-weight: 700; font-size: 0.95rem; margin-bottom: 6px;">';
      html += '<span>' + m.h + '</span><span style="color:'+(m.showGoalH?'var(--green)':'white')+'">' + goalH + m.sh + '</span>';
      html += '</div>';
      html += '<div style="display:flex; justify-content:space-between; font-weight: 700; font-size: 0.95rem;">';
      html += '<span>' + m.a + '</span><span style="color:'+(m.showGoalA?'var(--green)':'white')+'">' + m.sa + goalA + '</span>';
      html += '</div>';
      html += '</div>';
    });
    liveScoresGrid.innerHTML = html;
  }

  if (liveScoresGrid) {
    renderLiveScores();
    setInterval(function(){
      // Time increment
      if(Math.random() > 0.6) {
        var tm = liveMatches[Math.floor(Math.random()*liveMatches.length)];
        if(tm.time < 90 && !tm.showGoalH && !tm.showGoalA) tm.time++;
        renderLiveScores();
      }

      // Random Goal (approx every 10-15s for visual effect)
      if(Math.random() > 0.8) {
        var mIdx = Math.floor(Math.random()*liveMatches.length);
        var match = liveMatches[mIdx];

        if(match.showGoalH || match.showGoalA) return; // already animating

        var isHome = Math.random() > 0.5;
        if(isHome) { match.sh++; match.showGoalH = true; }
        else { match.sa++; match.showGoalA = true; }

        playGoalDing();
        renderLiveScores();

        // Remove highlight after 6 seconds
        setTimeout(function(){
          match.showGoalH = false;
          match.showGoalA = false;
          renderLiveScores();
        }, 6000);
      }
    }, 5000);
  }

})();
