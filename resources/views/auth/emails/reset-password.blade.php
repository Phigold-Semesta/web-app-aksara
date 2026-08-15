<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AKSARA LPSE Karawang</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0; background-color:#f4f4f5;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background-color:#064e3b; padding:24px; text-align:center;">
                            <span style="color:#ffffff; font-weight:900; letter-spacing:2px; font-size:18px;">
                                AKSARA <span style="color:#34d399;">LPSE</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h2 style="color:#064e3b; margin-top:0;">Permintaan Reset Password</h2>
                            <p style="color:#374151; font-size:14px; line-height:1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda pada sistem
                                <strong>AKSARA LPSE Karawang</strong>. Klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            <div style="text-align:center; margin: 32px 0;">
                                <a href="{{ $resetLink }}"
                                   style="background-color:#064e3b; color:#ffffff; text-decoration:none; padding:14px 32px; border-radius:10px; font-weight:800; font-size:13px; letter-spacing:1px; text-transform:uppercase; display:inline-block;">
                                    Reset Password
                                </a>
                            </div>

                            <p style="color:#6b7280; font-size:12px; line-height:1.6;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                                <a href="{{ $resetLink }}" style="color:#059669; word-break:break-all;">{{ $resetLink }}</a>
                            </p>

                            <p style="color:#6b7280; font-size:12px; line-height:1.6; margin-top:24px;">
                                Tautan ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini — password Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f9fafb; padding:16px; text-align:center;">
                            <span style="color:#9ca3af; font-size:11px;">&copy; {{ date('Y') }} AKSARA LPSE Karawang. All rights reserved.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>