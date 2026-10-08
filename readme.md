<h1 align="center">ABM CTF - Web Soruları</h1>

<p align="center">
  Bu depo, ABM CTF için özel olarak hazırlanmış, senaryo odaklı ve çok aşamalı 3 farklı web zafiyet sorusunu içermektedir.
</p>

---

## 🚩 CTF Soruları

### 1. Ghost Stream
**Kategori:** Web | **Etiketler:** `SSRF`, `LFI`, `CDN/Cache Bypass`, `Kaynak Kod Analizi`

Yıllardır yasadışı spor yayınları sağlayan ve siber istihbarat birimlerinin radarında olan büyük bir organizasyonun altyapısını çökertmekle görevlisiniz. Karşınıza çıkan platform, görünürde tamamen işlevsiz ve sahte bileşenlerle donatılmış sıradan bir web sitesi imajı çizerek asıl operasyonu gizliyor. Ancak arka planda, gelişmiş bir CDN ağı, özel yapılandırılmış bir önbellek (cache) katmanı ve yayınları dinamik olarak yönlendiren karmaşık bir parametre mimarisi çalışmaktadır. Göreviniz, sahte hata mesajlarının arasına gizlenmiş ipuçlarını takip ederek sistemin dosya çözümleme mekanizmasındaki zafiyetleri tespit etmektir. Cache katmanının arkasına saklanmış gerçek sunucu IP adresini bularak filtreleri atlatmalı, SSRF ve LFI tekniklerini harmanlayarak yayın motorunu manipüle etmeli ve sistemin kalbinde yer alan bayrak dosyasına ulaşmalısınız.

### 2. Chemical Horizon: Compromised Control Room
**Kategori:** Web & OSINT | **Etiketler:** `ICS/SCADA`, `IoT Zafiyetleri`, `2FA Atlatma`, `API Sömürüsü`

Modern bir kimyasal depolama tesisinin kalbi olan ICS/SCADA tabanlı kontrol paneli, gece saatlerinde dışarıdan müdahaleye uğrayarak tank basıncı ve kimyasal ısı değerlerinde felakete yol açabilecek düzensizlikler göstermeye başlamıştır. Sistemi kurtarabilecek tek yetkili olan sistem mühendisine ulaşılamamaktadır, ancak kendisinin evindeki IoT güvenlik kameraları üzerinden yönetim paneline erişim sağlanabileceğine dair kritik bir istihbarat alınmıştır. Bu kaotik ve zamanın daraldığı ortamda, bir siber güvenlik uzmanı olarak öncelikle ev içi kamera sistemine sızmalı ve ortamdaki dağınık nesnelerin arasında saklanan görsel ipuçlarını toplamalısınız. Elde ettiğiniz erişim bilgileri ve hash tabanlı görsel doğrulama (2FA) mekanizmasını atlattıktan sonra, sabotajcıların bozduğu 9 farklı sensör değerini API manipülasyonu ile referans aralıklarına döndürerek termal bir patlamayı engellemeli ve tesisi güvenli duruma getirmelisiniz.

### 3. InboxRansom: Quarantined Payload Alert
**Kategori:** Web | **Etiketler:** `Bilgi İfşası (Info Disclosure)`, `API Sömürüsü`, `Kaynak Kod Analizi`

Havacılık teknolojileri devi FlightCore firmasının kurumsal iç iletişim platformu olan MailSuite Enterprise, son günlerde ağ üzerinde yaşanan ve IT ekibi tarafından sürekli örtbas edilen şüpheli yavaşlamaların ardından otomatik bir karantina alarmı üretmiştir. Tüm personele gönderildiği anda "Critical Patch Deployment" başlığıyla saniyeler içinde geri çekilen ve içeriği erişime kapatılan bu yönetsel e-posta, aslında sistemdeki bir fidye yazılımı ihlalinin kanıtlarını barındırmaktadır. Şirket yönetiminin gizli yürüttüğü bu soruşturmada, yetkilendirilmiş bir çalışan hesabı ile sisteme giriş yaparak görünürde erişilemez olan bu mailin peşine düşmelisiniz. Front-end kaynak kodlarında unutulmuş gizli telemetri API uç noktalarını ve yönetici token değerlerini tespit etmeli, HTTP yanıtlarından sızdırılan UUID bilgilerini kullanarak sistemi manipüle etmeli ve karantinaya alınan e-postanın içindeki gerçek saldırı detayları ile bayrağı gün yüzüne çıkarmalısınız.
