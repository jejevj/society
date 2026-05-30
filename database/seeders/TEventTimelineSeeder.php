<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventTimelineSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_timeline')->truncate();
        DB::table('t_event_timeline')->insert([

            // DAY 1 - 2026-07-01
            ['kode_timeline'=>'TL-EV260529-001','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'08:00:00','jam_selesai'=>'09:00:00','judul_sesi'=>'Registration & Welcome Coffee','deskripsi_sesi'=>'Peserta melakukan registrasi ulang dan pengambilan name tag di lobby utama. Tersedia coffee break dan networking informal.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-002','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'09:00:00','jam_selesai'=>'10:00:00','judul_sesi'=>'Opening Ceremony & Inaugural Address','deskripsi_sesi'=>'Pembukaan resmi ScienceBank Society Summit oleh Ketua Umum dan sambutan dari tamu kehormatan. Dilanjutkan dengan pelantikan Presiden periode baru.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-003','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'10:15:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Keynote: Future of Science in National Development','deskripsi_sesi'=>'Keynote speech oleh pembicara utama membahas peran ilmu pengetahuan dalam mendorong pembangunan nasional berkelanjutan. Sesi tanya jawab terbuka untuk peserta.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-004','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'12:00:00','jam_selesai'=>'13:30:00','judul_sesi'=>'Lunch Break & Networking','deskripsi_sesi'=>'Istirahat makan siang. Kesempatan networking antar peserta, peneliti, dan mitra industri.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-005','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'13:30:00','jam_selesai'=>'15:30:00','judul_sesi'=>'Paper Presentation Session 1 - Technology & Innovation','deskripsi_sesi'=>'Sesi presentasi paper terpilih bertema Teknologi dan Inovasi. Setiap presenter mendapat waktu 15 menit presentasi dan 5 menit Q&A.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-006','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'15:30:00','jam_selesai'=>'17:00:00','judul_sesi'=>'Panel Discussion: Science-Industry Collaboration','deskripsi_sesi'=>'Diskusi panel oleh 4 pakar dari akademisi dan industri membahas model kolaborasi riset yang efektif. Moderasi oleh dewan redaksi ScienceBank.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],

            // DAY 2 - 2026-07-02
            ['kode_timeline'=>'TL-EV260529-007','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'08:30:00','jam_selesai'=>'10:00:00','judul_sesi'=>'Workshop: Research Methodology & Data Analysis','deskripsi_sesi'=>'Workshop intensif tentang metodologi penelitian kuantitatif dan analisis data menggunakan tools modern. Peserta dianjurkan membawa laptop.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-008','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'10:15:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Paper Presentation Session 2 - Economics & Social Science','deskripsi_sesi'=>'Sesi presentasi paper terpilih bertema Ekonomi dan Ilmu Sosial. Setiap presenter mendapat waktu 15 menit presentasi dan 5 menit Q&A.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-009','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'12:00:00','jam_selesai'=>'13:30:00','judul_sesi'=>'Lunch Break','deskripsi_sesi'=>'Istirahat makan siang.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-010','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'13:30:00','jam_selesai'=>'15:00:00','judul_sesi'=>'Plenary Session: Environmental Science & Sustainability','deskripsi_sesi'=>'Sesi pleno membahas isu keberlanjutan lingkungan dan kontribusi sains dalam mitigasi perubahan iklim di kawasan Asia Tenggara.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-011','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'19:00:00','jam_selesai'=>'22:00:00','judul_sesi'=>'Gala Dinner & Award Night','deskripsi_sesi'=>'Malam penghargaan untuk paper terbaik dan kontributor ScienceBank Society. Dress code: formal.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],

            // DAY 3 - 2026-07-03
            ['kode_timeline'=>'TL-EV260529-012','kode_event'=>'EV260529145400','hari_ke'=>3,'tanggal_timeline'=>'2026-07-03','jam_mulai'=>'09:00:00','jam_selesai'=>'11:00:00','judul_sesi'=>'Breakout Session: Health & Biomedical Sciences','deskripsi_sesi'=>'Sesi breakout khusus bidang kesehatan dan biomedis. Diskusi mendalam tentang inovasi terbaru dalam diagnostik dan terapi berbasis teknologi.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-013','kode_event'=>'EV260529145400','hari_ke'=>3,'tanggal_timeline'=>'2026-07-03','jam_mulai'=>'11:00:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Closing Ceremony & Certificate Distribution','deskripsi_sesi'=>'Penutupan resmi summit, pembagian sertifikat keikutsertaan, dan foto bersama seluruh peserta dan panitia.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
