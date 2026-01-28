<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Maintenance</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0f172a; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .container { max-width: 600px; margin: 20px auto; background-color: #1e293b; border-radius: 16px; overflow: hidden; border: 1px solid #334155; }
        .header { background-color: #f97316; padding: 30px; text-align: center; }
        .content { padding: 40px; color: #cbd5e1; line-height: 1.6; text-align: left; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; background-color: #0f172a; border-top: 1px solid #334155; }
        .button-wrapper { text-align: center; margin: 30px 0; }
        .button { 
            display: inline-block; 
            padding: 14px 35px; 
            background-color: #f97316; 
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        .warning-box {
            background-color: rgba(249, 115, 22, 0.1);
            border-left: 4px solid #f97316;
            padding: 15px;
            margin-top: 25px;
            border-radius: 4px;
        }
        .timer-text {
            color: #f97316;
            font-weight: bold;
        }
        .time-label {
            display: block;
            margin-top: 10px;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; color: #ffffff; text-transform: uppercase; letter-spacing: 2px;">Sistem Maintenance</h2>
            <div style="font-size: 12px; color: #fed7aa;">Kependudukan Digital Desa</div>
        </div>

        <div class="content">
            <h3 style="color: #ffffff; margin-top: 0;">Halo, {{ $name }}!</h3>
            <p>Kami menerima permintaan untuk mengatur ulang password akun Anda. Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini dengan aman.</p>
            
            <p>Untuk melanjutkan proses pembuatan password baru, silakan klik tombol di bawah ini:</p>
            
            <div class="button-wrapper">
                <a href="{{ $url }}" class="button">Atur Ulang Password</a>
                <span class="time-label">
                    Dikirim pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H:i:s') }} WIB
                </span>
            </div>

            <div class="warning-box">
                <p style="margin: 0; font-size: 13px; color: #cbd5e1;">
                    <strong style="color: #f97316;">⚠️ KEAMANAN TINGGI:</strong> Link ini hanya berlaku selama 60 detik demi keamanan akun Anda.
                    <br><br>
                    Link akan otomatis <span class="timer-text">HANGUS PADA PUKUL {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->addSeconds(60)->format('H:i:s') }} WIB</span>.
                </p>
            </div>

            <p style="margin-top: 25px; font-size: 12px; color: #94a3b8; border-top: 1px solid #334155; padding-top: 20px;">
                Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin tautan berikut ke browser Anda:<br>
                <span style="color: #f97316; word-break: break-all;">{{ $url }}</span>
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">&copy; 2026 Sistem Maintenance Kependudukan. All rights reserved.</p>
            <p style="margin: 5px 0 0 0; font-size: 10px;">Email otomatis, mohon tidak dibalas.</p>
        </div>
    </div>
</body>
</html>