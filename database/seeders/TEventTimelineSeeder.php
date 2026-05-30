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
            ['kode_timeline'=>'TL-EV260529-001','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'08:00:00','jam_selesai'=>'09:00:00','judul_sesi'=>'Registration & Welcome Coffee','deskripsi_sesi'=>'Participants check in and collect name tags at the main lobby. Coffee break and informal networking available.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-002','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'09:00:00','jam_selesai'=>'10:00:00','judul_sesi'=>'Opening Ceremony & Inaugural Address','deskripsi_sesi'=>'Official opening of the ScienceBank Society Summit by the Chairperson, followed by remarks from honored guests and inauguration of the new President.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-003','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'10:15:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Keynote: Future of Science in National Development','deskripsi_sesi'=>'Keynote speech by the main speaker on the role of science in driving sustainable national development. Open Q&A session for all participants.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-004','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'12:00:00','jam_selesai'=>'13:30:00','judul_sesi'=>'Lunch Break & Networking','deskripsi_sesi'=>'Lunch break. Networking opportunity among participants, researchers, and industry partners.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-005','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'13:30:00','jam_selesai'=>'15:30:00','judul_sesi'=>'Paper Presentation Session 1 - Technology & Innovation','deskripsi_sesi'=>'Selected paper presentations on the theme of Technology and Innovation. Each presenter is given 15 minutes to present and 5 minutes for Q&A.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-006','kode_event'=>'EV260529145400','hari_ke'=>1,'tanggal_timeline'=>'2026-07-01','jam_mulai'=>'15:30:00','jam_selesai'=>'17:00:00','judul_sesi'=>'Panel Discussion: Science-Industry Collaboration','deskripsi_sesi'=>'Panel discussion by 4 experts from academia and industry on effective research collaboration models. Moderated by the ScienceBank editorial board.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],

            // DAY 2 - 2026-07-02
            ['kode_timeline'=>'TL-EV260529-007','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'08:30:00','jam_selesai'=>'10:00:00','judul_sesi'=>'Workshop: Research Methodology & Data Analysis','deskripsi_sesi'=>'Intensive workshop on quantitative research methodology and data analysis using modern tools. Participants are encouraged to bring a laptop.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-008','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'10:15:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Paper Presentation Session 2 - Economics & Social Science','deskripsi_sesi'=>'Selected paper presentations on the theme of Economics and Social Sciences. Each presenter is given 15 minutes to present and 5 minutes for Q&A.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-009','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'12:00:00','jam_selesai'=>'13:30:00','judul_sesi'=>'Lunch Break','deskripsi_sesi'=>'Lunch break.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-010','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'13:30:00','jam_selesai'=>'15:00:00','judul_sesi'=>'Plenary Session: Environmental Science & Sustainability','deskripsi_sesi'=>'Plenary session addressing environmental sustainability issues and the contribution of science to climate change mitigation in Southeast Asia.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-011','kode_event'=>'EV260529145400','hari_ke'=>2,'tanggal_timeline'=>'2026-07-02','jam_mulai'=>'19:00:00','jam_selesai'=>'22:00:00','judul_sesi'=>'Gala Dinner & Award Night','deskripsi_sesi'=>'Award ceremony for the best papers and ScienceBank Society contributors. Dress code: formal.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],

            // DAY 3 - 2026-07-03
            ['kode_timeline'=>'TL-EV260529-012','kode_event'=>'EV260529145400','hari_ke'=>3,'tanggal_timeline'=>'2026-07-03','jam_mulai'=>'09:00:00','jam_selesai'=>'11:00:00','judul_sesi'=>'Breakout Session: Health & Biomedical Sciences','deskripsi_sesi'=>'Dedicated breakout session for health and biomedical sciences. In-depth discussions on the latest innovations in diagnostics and technology-based therapeutics.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['kode_timeline'=>'TL-EV260529-013','kode_event'=>'EV260529145400','hari_ke'=>3,'tanggal_timeline'=>'2026-07-03','jam_mulai'=>'11:00:00','jam_selesai'=>'12:00:00','judul_sesi'=>'Closing Ceremony & Certificate Distribution','deskripsi_sesi'=>'Official closing of the summit, distribution of participation certificates, and group photo with all participants and committee members.','status_timeline'=>'Y','created_by_timeline'=>'admin','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
