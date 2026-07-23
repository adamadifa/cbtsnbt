<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat - {{ $examResult->user->name }}</title>
    <style>
        @page { size: landscape; margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; background-color: #fff; color: #1e293b; }
        .certificate-container { position: relative; width: 100%; height: 100%; padding: 60px; box-sizing: border-box; overflow: hidden; }
        
        /* Decorative Background */
        .bg-pattern { position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: -1; }
        .border-line { position: absolute; top: 20px; left: 20px; right: 20px; bottom: 20px; border: 2px solid #e2e8f0; z-index: -1; }
        .border-inner { position: absolute; top: 30px; left: 30px; right: 30px; bottom: 30px; border: 1px solid #f1f5f9; z-index: -1; }
        
        .corner-decoration { position: absolute; width: 150px; height: 150px; background-color: #4f46e5; opacity: 0.05; border-radius: 50%; }
        .top-left { top: -75px; left: -75px; }
        .bottom-right { bottom: -75px; right: -75px; width: 250px; height: 250px; }

        .content { text-align: center; margin-top: 40px; }
        .logo { width: 60px; height: 60px; background-color: #4f46e5; border-radius: 15px; margin: 0 auto 20px; display: block; }
        
        .title { font-size: 42px; font-weight: 800; color: #1e293b; margin: 20px 0; text-transform: uppercase; letter-spacing: 4px; }
        .subtitle { font-size: 16px; font-weight: 600; color: #64748b; margin-bottom: 40px; letter-spacing: 2px; text-transform: uppercase; }
        
        .recipient-label { font-size: 14px; font-style: italic; color: #94a3b8; margin-bottom: 15px; }
        .recipient-name { font-size: 36px; font-weight: 800; color: #4f46e5; border-bottom: 2px solid #f1f5f9; display: inline-block; padding: 0 40px 10px; margin-bottom: 30px; min-width: 400px; }
        
        .description { font-size: 16px; line-height: 1.6; color: #475569; max-width: 700px; margin: 0 auto 40px; font-weight: 500; }
        
        .score-box { background-color: #f8fafc; padding: 15px 30px; border-radius: 20px; display: inline-block; border: 1px solid #e2e8f0; margin-bottom: 50px; }
        .score-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px; }
        .score-value { font-size: 24px; font-weight: 800; color: #1e293b; }

        .footer { margin-top: 50px; position: relative; }
        .signature-area { width: 200px; border-top: 1px solid #e2e8f0; margin: 20px auto; padding-top: 10px; }
        .signer-name { font-size: 14px; font-weight: 800; color: #1e293b; }
        .signer-role { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }

        .verification { position: absolute; bottom: 60px; right: 60px; text-align: right; }
        .qr-code { width: 80px; height: 80px; background-color: #f1f5f9; display: block; margin-left: auto; border: 5px solid #fff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .id-number { font-size: 9px; color: #94a3b8; font-family: monospace; margin-top: 8px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-line"></div>
        <div class="border-inner"></div>
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration bottom-right"></div>

        <div class="content">
            <div class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" style="padding: 12px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            
            <div class="subtitle">Sertifikat Penyelesaian</div>
            <div class="title">PIAGAM PENGHARGAAN</div>
            
            <div class="recipient-label">Dengan bangga diberikan kepada:</div>
            <div class="recipient-name">{{ strtoupper($examResult->user->name) }}</div>
            
            <div class="description">
                Atas keberhasilan menyelesaikan sesi ujian <span style="font-weight: 800; color: #1e293b;">"{{ $examResult->examSession->title }}"</span> pada platform Lulus SNBT dengan performa yang sangat baik.
            </div>

            <div class="score-box">
                <div class="score-label">Total Skor Akhir</div>
                <div class="score-value">{{ $examResult->total_score }}</div>
            </div>

            <div class="footer">
                <div class="signer-name">{{ config('app.name') }} Administrator</div>
                <div class="signer-role">Sistem Sertifikasi Digital</div>
                <div class="signature-area"></div>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 10px;">{{ $examResult->finished_at->format('d F Y') }}</div>
            </div>
        </div>

        <div class="verification">
            {{-- Simplified QR Placeholder --}}
            <div class="qr-code">
                 <svg viewBox="0 0 100 100" style="padding: 10px;">
                    <path d="M0 0h30v30H0zM70 0h30v30H70zM0 70h30v30H0zM40 40h20v20H40z" fill="#4f46e5" />
                    <path d="M40 0h20v10H40zM80 40h20v20H80zM0 40h10v20H0zM40 80h20v20H40z" fill="#94a3b8" />
                 </svg>
            </div>
            <div class="id-number">VERIFY-ID: {{ substr(md5($examResult->id), 0, 12) }}</div>
        </div>
    </div>
</body>
</html>
