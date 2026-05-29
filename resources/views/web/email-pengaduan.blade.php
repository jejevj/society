<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaduan Satu Data Pertahanan</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:30px 0;">
        <tr>
            <td align="center">

                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:#0d6efd; padding:25px 35px; color:#ffffff;">
                            <h2 style="margin:0; font-size:24px;">
                                Pengaduan Satu Data Pertahanan
                            </h2>
                            <p style="margin:8px 0 0; font-size:14px; opacity:0.9;">
                                Informasi pengaduan yang dikirim melalui website Satu Data Pertahanan
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px;">

                            <table width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td width="180" style="padding:12px 0; font-weight:bold; color:#333;">
                                        Nama Lengkap
                                    </td>
                                    <td style="padding:12px 0; color:#555;">
                                        {{ $nama }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; font-weight:bold; color:#333;">
                                        Email
                                    </td>
                                    <td style="padding:12px 0; color:#555;">
                                        {{ $email }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; font-weight:bold; color:#333;">
                                        Nomor KTP/Identitas
                                    </td>
                                    <td style="padding:12px 0; color:#555;">
                                        {{ $ktp }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px 0; font-weight:bold; color:#333;">
                                        Tanggal Pengaduan
                                    </td>
                                    <td style="padding:12px 0; color:#555;">
                                        {{ $tanggal }}
                                    </td>
                                </tr>

                                <tr>
                                    <td valign="top" style="padding:12px 0; font-weight:bold; color:#333;">
                                        Isi Pengaduan
                                    </td>
                                    <td style="padding:12px 0; color:#555; line-height:1.8;">
                                        {!! nl2br(e($pesan)) !!}
                                    </td>
                                </tr>

                            </table>

                            <!-- NOTE -->
                            <div style="margin-top:35px; padding:18px; background:#f8f9fa; border-left:4px solid #0d6efd; border-radius:6px; color:#555; font-size:14px; line-height:1.7;">
                                Pesan ini dikirim secara otomatis melalui formulir hubungi kami satu data pertahanan.
                                Mohon untuk tidak membalas email ini secara langsung.
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f8f9fa; padding:20px 35px; text-align:center; font-size:13px; color:#888;">
                            © {{ date('Y') }} Satu Data Pertahanan Kementrian Pertahanan Republik Indonesia.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>