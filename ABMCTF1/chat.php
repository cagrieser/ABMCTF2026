<?php
header('Content-Type: application/json; charset=utf-8');

$chatPool = [
    ['user' => 'GolKrali_34', 'msg' => 'GOOOLLLL! 🎉🇹🇷', 'badge' => 'vip'],
    ['user' => 'FutbolDelisi', 'msg' => 'Ne maç be! Türkiye çok iyi oynuyor', 'badge' => null],
    ['user' => 'AyYildiz2026', 'msg' => 'Hadi Türkiyem! 🇹🇷🇹🇷🇹🇷', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => 'Yayın kalitesi düştüyse sunucu değiştirin, altyapı şu an %90 yükte!', 'badge' => 'admin'],
    ['user' => 'SporAsi', 'msg' => 'Gürcistan da fena değil aslında, Kvaratskhelia çok tehlikeli adam dikkat etmek lazım.', 'badge' => null],
    ['user' => 'MaçKolik', 'msg' => 'Bu penaltıydı ya! Hakem resmen uyuyor, VAR\'a bile gitmedi.', 'badge' => null],
    ['user' => 'CimBom_1905', 'msg' => 'GS\'liler fark yaratıyor 💛❤️ Barış Alper resmen traktör gibi eziyor defansı.', 'badge' => 'vip'],
    ['user' => 'TribunFanatik', 'msg' => 'Stream çok kasıyor bende, F5 atıyorum yine aynı.', 'badge' => null],
    ['user' => 'GolAvcisi_07', 'msg' => 'İkinci yarı daha iyi olacak, 1-0 tehlikeli skor beyler acil 2\'yi bulmamız lazım.', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => '⚠️ Avrupa #1 yoğun, #2 ye geçin. Küfür edenler süresiz banlanacaktır!', 'badge' => 'admin'],
    ['user' => 'Fenerbull', 'msg' => 'GOL GELİYOR HİSSEDİYORUM 🔥 Arda Güler yapacak şovunu birazdan.', 'badge' => null],
    ['user' => 'SesliTribun', 'msg' => 'Türkiye Türkiye! 🎵 Kırmızı beyaz şampiyon Türkiye!', 'badge' => null],
    ['user' => 'FutbolTaktik', 'msg' => 'Abi orta sahada çok boşluk bırakıyoruz, Gürcistan kontra ataklarda hızlı çıkıyor, Hakan\'ın oyunu soğutması lazım.', 'badge' => 'vip'],
    ['user' => 'KaanBey', 'msg' => 'Beyler maç kaç kaç ben yeni girdim yayına?', 'badge' => null],
    ['user' => 'Esra_TR', 'msg' => '1-0 öndeyiz ama ömrümden ömür gitti yemin ederim.', 'badge' => null],
    ['user' => 'Dark_Hacker', 'msg' => 'Kaçak yayında bile 4K izliyoruz admin helal olsun sana altyapı efsane.', 'badge' => 'vip'],
    ['user' => 'VatanMillet', 'msg' => 'İkinci gol gelmezse bu Gürcüler bize ecel teri döktürür, çok saldırıyorlar.', 'badge' => null],
    ['user' => 'Kartal_Ruhu', 'msg' => 'Semih nerede abi alsın artık şu çocuğu oyuna da gol görelim!', 'badge' => null],
    ['user' => 'YayıncıMod', 'msg' => 'Chat yavaş mod aktiftir. Lütfen spam yapmayınız.', 'badge' => 'admin'],
    ['user' => 'Crazy_Boy99', 'msg' => 'Ahhhh direkten döndü! Nasıl girmez o ya nasıl!', 'badge' => null],
    ['user' => 'ZeynepKSK', 'msg' => 'Kalecimiz bugün efsane oynuyor, o son şutu nasıl çıkardı öyle maşallah.', 'badge' => null],
    ['user' => 'Batu_1907', 'msg' => 'Fenerbahçe lobisi olmasa bu takım iş yapamaz asdadasd', 'badge' => null],
    ['user' => 'AslanParcasi', 'msg' => 'Kulüpçülük yapmayın lan milli maç burası! Hepimiz Türkiyeyiz 🇹🇷', 'badge' => 'vip'],
    ['user' => 'TaktikEksperi', 'msg' => 'Defansın arasına atılan uzun toplarda çok aksıyoruz, hoca bir hamle yapmalı artık.', 'badge' => null],
    ['user' => 'Ali_Vefa', 'msg' => '2-0 olacak yazın kenara, Kenan Yıldız fişi çekecek.', 'badge' => null],
    ['user' => 'YorgunSavasci', 'msg' => 'Yayın dondu dondu donduuuu!', 'badge' => null],
    ['user' => 'Korsan_Avcisi', 'msg' => 'Bende donma falan yok sizin netiniz patates beyler.', 'badge' => null],
    ['user' => 'Emre_Kaya', 'msg' => 'Şut atın artık ceza sahasına girmeden şut çekin şu kaleciye!', 'badge' => null],
    ['user' => 'MilliAsik', 'msg' => 'Hadi aslanlarım, hadi koçlarım! Bütün Avrupa bizi izliyor şu an.', 'badge' => 'vip'],
    ['user' => 'Gurbetci_06', 'msg' => 'Almanya\'dan selamlar, burada herkes sokaklarda maçı izliyor, atın şu ikinciyi de kutlamalara başlayalım.', 'badge' => null],
    ['user' => 'DefansBakani', 'msg' => 'Merih bugün duvar gibi yemin ederim adam geçirmiyor.', 'badge' => null],
    ['user' => 'Ahmet_55', 'msg' => 'Ayağınızda tutun şu topu şişirip durmayın ileriye!', 'badge' => null],
    ['user' => 'Streamer_Kral', 'msg' => 'Yayın 10 numara 5 yıldız, donanlar VPN kapatsın.', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => 'Sohbette siyaset ve kulüp tartışması yapan 3 kişi uzaklaştırıldı.', 'badge' => 'admin'],
    ['user' => 'Fenerli_Taha', 'msg' => 'Hakem tam bir fiyasko, bütün takdir haklarını Gürcistan\'dan yana kullanıyor.', 'badge' => null],
    ['user' => 'Goztepeli_Efe', 'msg' => 'İzmir\'den sevgiler, hadi be çocuklar atın bir tane daha rahatlayalım.', 'badge' => null],
    ['user' => 'SiyahBeyazAsk', 'msg' => 'Orta sahada İsmail Yüksek eksikliği çok belli oluyor, top kesemiyoruz.', 'badge' => null],
    ['user' => 'RastgeleBiri', 'msg' => 'Vurun ulan vurun acımayın!', 'badge' => null],
    ['user' => 'Kaan_Reis', 'msg' => 'Kvaratskhelia\'yı ikili sıkıştırmaya almaları lazım, adam tek başına bizim sağ kanadı yıktı.', 'badge' => 'vip'],
    ['user' => 'Cem_Yilmaz_Fan', 'msg' => 'Bir cisim yaklaşıyor... 2. gol geliyor hissediyorum!', 'badge' => null],
    ['user' => 'MertCan_01', 'msg' => 'Adana Demirsporlu Yusuf\'u da alsaydı kadroya varya şimdi şov yapardı.', 'badge' => null],
    ['user' => 'Ayse_22', 'msg' => 'Ofsayt yok muydu o pozisyonda? Kalbim duracaktı az kalsın.', 'badge' => null],
    ['user' => 'TurkOgluTurk', 'msg' => 'Hadi aslanlarım gösterin Türk\'ün gücünü 🐺🇹🇷', 'badge' => 'vip'],
    ['user' => 'Yusuf_GS', 'msg' => 'Barış Alper yoruldu artık değiştirmesi lazım hocanın.', 'badge' => null],
    ['user' => 'Veli_Dayi', 'msg' => 'Gençler maç bitmek üzere mi kaçıncı dakika?', 'badge' => null],
    ['user' => 'Batu_TR', 'msg' => 'Daha var dayı sakin ol 75. dakika falan.', 'badge' => null],
    ['user' => 'Cyber_Wolf', 'msg' => 'Sistemin anlık durumunu merak edenler için F12 console\'a bakın :D Şaka şaka maç izleyin.', 'badge' => 'vip'],
    ['user' => 'Hakan_Abi', 'msg' => 'Bravo kaleci, bravo oğlum! Çizgiden çıkardı resmen.', 'badge' => null],
    ['user' => 'Reis_Sedat', 'msg' => 'Hakem bitir hocaaaaa bitirrrrrr', 'badge' => null],
    ['user' => 'Futbol_Kurdu', 'msg' => 'Adamlar risk almaya başladı, arkada çok boşluk bırakıyorlar. Tam Arda\'lık maç oldu şu an.', 'badge' => null],
    ['user' => 'Caner_1907', 'msg' => 'Ayağa pas yapın abi dikmeyin şu topu!', 'badge' => null],
    ['user' => 'Buse_Ceylan', 'msg' => 'Ya gol olsun artık lütfen stres oldum burada.', 'badge' => null],
    ['user' => 'MilliDava', 'msg' => '2. gol gelmezse kriz geçiricem yemin ederim. 🇹🇷', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => 'Arka planda sunucu kapasitesi artırılıyor, 5 saniyelik bir kesinti olabilir.', 'badge' => 'admin'],
    ['user' => 'Kara_Kartal_1903', 'msg' => 'Mert kurtarıyor valla Beşiktaşın aslanı!', 'badge' => null],
    ['user' => 'OyunKolu', 'msg' => 'Bu Gürcistan fifada bu kadar iyi değil gerçekte nasıl oynuyorlar böyle', 'badge' => null],
    ['user' => 'Selim_44', 'msg' => 'Faul verdi inanılmaz! Dokunmadı bile adam!', 'badge' => null],
    ['user' => 'Gorkem_Tr', 'msg' => 'Hücum hoca hücum defansa çekilmek intihardır bu dakikada.', 'badge' => null],
    ['user' => 'Yasar_Kemal', 'msg' => 'O top ağlarla buluşacak başka yolu yok!', 'badge' => 'vip'],
    ['user' => 'Enes_Batur_Fan', 'msg' => 'Chat çok hızlı okuyamıyorum', 'badge' => null],
    ['user' => 'Samet_55', 'msg' => 'Kerem Aktürkoğlu füzeyi yollayacak birazdan bekleyin.', 'badge' => null],
    ['user' => 'Hasan_Huseyin', 'msg' => 'Müthiş paslaştık ama son vuruş eksik be usta.', 'badge' => null],
    ['user' => 'Efe_Kaan', 'msg' => 'VAR\'a gidiyor mu hakem? El var gibiydi orada.', 'badge' => null],
    ['user' => 'Deli_Kadir', 'msg' => 'Yok lan ne VAR\'ı devam ettirdi kör hakem.', 'badge' => null],
    ['user' => 'Kirmizi_Beyaz', 'msg' => 'Kanları kırmızı yüzleri ak, hadi bizim çocuklar! 🇹🇷', 'badge' => null],
    ['user' => 'Ali_Bozkurt', 'msg' => 'Kontra atağa çok yavaş çıkıyoruz, biraz hızlı beyler.', 'badge' => null],
    ['user' => 'Vip_Kullanici', 'msg' => 'Yayın kalitesi 1080p cillop gibi, korsan morsan ama iş yapıyor site.', 'badge' => 'vip'],
    ['user' => 'Siber_Mete', 'msg' => 'Sunucunun pingi fırladı Gürcüler bot mu basıyor naptı :)', 'badge' => null],
    ['user' => 'Emine_K', 'msg' => 'Hakan Çalhanoğlu çok klas oynuyor maşallah.', 'badge' => null],
    ['user' => 'Taha_FB', 'msg' => 'Direkten döndü offfffffffff', 'badge' => null],
    ['user' => 'Berkay_09', 'msg' => 'Nasıl girmez o top çıldıracağım!', 'badge' => null],
    ['user' => 'Uzman_Taktikci', 'msg' => 'Kenan çok yoruldu defansa yardıma gelemiyor artık, sol taraf çöktü.', 'badge' => 'vip'],
    ['user' => 'Admin_Korsan', 'msg' => 'Lütfen izleme sayfasına F5 atmayın, sunucuya gereksiz yük biniyor!', 'badge' => 'admin'],
    ['user' => 'Yalniz_Kurt', 'msg' => 'Son 10 dakika, dayanın beyler!', 'badge' => null],
    ['user' => 'Osman_Dayi', 'msg' => 'Gol yedik mi gençler benim yayın geriden geliyor?', 'badge' => null],
    ['user' => 'Burak_Y', 'msg' => 'Hayır dayı yemedik 1-0 devam ama ecel terleri döküyoruz.', 'badge' => null],
    ['user' => 'Cagatay_TR', 'msg' => 'Gol geliyor 2 oldu 2!!', 'badge' => null],
    ['user' => 'Merve_Nur', 'msg' => 'Trollüyor inanmayın gol falan yok hala 1-0', 'badge' => null],
    ['user' => 'Asim_Abi', 'msg' => 'Kalpten götüreceksiniz adamı yalan söylemeyin lan.', 'badge' => null],
    ['user' => 'Fatih_Reis', 'msg' => 'Topu dikin ileri vakit geçirin artık risk almanın alemi yok.', 'badge' => 'vip'],
    ['user' => 'Kaan_BJK', 'msg' => 'Orkun bugün beklentinin çok altında kaldı, top eziyor sürekli.', 'badge' => null],
    ['user' => 'TurkGucu', 'msg' => 'Avrupa Fatihi Türkiye! Eze eze yeneceğiz!', 'badge' => null],
    ['user' => 'Musti_98', 'msg' => 'Sakatlık var galiba oyun durdu.', 'badge' => null],
    ['user' => 'Esra_TR', 'msg' => 'İnşallah ciddi bir şeyi yoktur oyuncumuzun.', 'badge' => null],
    ['user' => 'Batu_1907', 'msg' => 'Yok ya numaradan yatıyor vakit geçirmek için, klas hareketler bunlar.', 'badge' => null],
    ['user' => 'GolKrali_34', 'msg' => 'Hocam bitir artık 90 dakika oldu yeterrr!', 'badge' => 'vip'],
    ['user' => 'Tribun_Genc', 'msg' => 'Uzatma kaç dakika verdi?', 'badge' => null],
    ['user' => 'Siyah_Beyaz_1903', 'msg' => '+5 verdi bitemez bu maç ya.', 'badge' => null],
    ['user' => 'Yasin_Kaya', 'msg' => 'Allahım sen koru yemin ederim heyecandan ellerim titriyor.', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => 'Son dakikalar, yayında kopma olmaması için chat yavaşlatıldı.', 'badge' => 'admin'],
    ['user' => 'CimBomlu_Kemal', 'msg' => 'Taç bizim kullanın da bitsin şu iş.', 'badge' => null],
    ['user' => 'Fenerbull', 'msg' => 'Kontraya çıkıyoruz boş kaleeeeeee', 'badge' => null],
    ['user' => 'Hakan_Abi', 'msg' => 'Vurrrrrr golll olsun o artıkkk', 'badge' => null],
    ['user' => 'Milli_Ruh', 'msg' => 'At onu Arda at onu canım benim!', 'badge' => 'vip'],
    ['user' => 'Crazy_Boy99', 'msg' => 'GOLLLLLLLLLLL BE GOLLLLLLLLL', 'badge' => null],
    ['user' => 'ZeynepKSK', 'msg' => 'GOOOOOOOOOOOOOOOOOOLLLLLLL 2-0 OLDUUUUU', 'badge' => null],
    ['user' => 'FutbolDelisi', 'msg' => 'ALLAHIM GOOOOOOL FİŞİ ÇEKTİKKKKKK', 'badge' => null],
    ['user' => 'Kartal_Ruhu', 'msg' => 'HELAL OLSUN BE SİZE BOŞ KALEYE YAZDIIIII', 'badge' => null],
    ['user' => 'Gurbetci_06', 'msg' => 'ALMANYA YIKILIYOR ŞU AN GOOOOOLL', 'badge' => null],
    ['user' => 'Admin_Korsan', 'msg' => 'GOOOOOOOOOOLLLLL 🇹🇷🇹🇷🇹🇷🇹🇷', 'badge' => 'admin'],
    ['user' => 'GolAvcisi_07', 'msg' => 'İşte bu be işte bu bitti bu maç!', 'badge' => null],
    ['user' => 'SporAsi', 'msg' => 'Muazzam bir depar muazzam bir gol helal olsun.', 'badge' => null],
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $count = isset($_GET['count']) ? min(20, max(1, intval($_GET['count']))) : 10;
    shuffle($chatPool);
    $messages = array_slice($chatPool, 0, $count);
    foreach ($messages as &$m) {
        $m['id'] = 'msg_' . substr(md5(rand()), 0, 8);
        $m['timestamp'] = date('H:i:s', time() - rand(0, 300));
    }
    echo json_encode(['channel' => 'tur-geo-live', 'online_users' => rand(800, 4500), 'messages' => $messages], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['message'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'ERR_EMPTY_MESSAGE']);
        exit;
    }
    $msg = htmlspecialchars(strip_tags(trim($input['message'])), ENT_QUOTES, 'UTF-8');
    $nick = isset($input['nickname']) ? htmlspecialchars(strip_tags(trim($input['nickname'])), ENT_QUOTES, 'UTF-8') : 'Anonim';
    echo json_encode(['status' => 'sent', 'id' => 'msg_' . substr(md5(time()), 0, 8), 'nickname' => $nick, 'message' => $msg], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'ERR_METHOD', 'allowed' => ['GET', 'POST']]);
}
