<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ceylon AG Notification' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 24px 12px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .logo-img {
            max-height: 50px;
            width: auto;
            background: #ffffff;
            padding: 8px 14px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 12px;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 4px;
            color: #ffffff;
        }
        .sub-header {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 2px;
            color: #ecfdf5;
        }
        .body-content {
            padding: 32px 28px;
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }
        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .data-label {
            color: #64748b;
            font-weight: 600;
            width: 40%;
        }
        .data-val {
            color: #0f172a;
            font-weight: 700;
        }
        .btn-container {
            text-align: center;
            margin: 28px 0 16px;
        }
        .btn {
            display: inline-block;
            background-color: #059669;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            line-height: 1.5;
        }
        .footer a {
            color: #059669;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" class="logo-img">
            @endif
            <div class="brand-name">CEYLON AG</div>
            <div class="sub-header">Agricultural Distribution & Partner Network</div>
        </div>

        <div class="body-content">
            {{ $slot }}
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>Ceylon AG (Pvt) Ltd</strong>. All rights reserved.<br>
            Support Email: <a href="mailto:{{ config('mail.from.address', 'info@ceylonagromarketing.lk') }}">{{ config('mail.from.address', 'info@ceylonagromarketing.lk') }}</a> &bull; <a href="{{ config('app.url', 'http://localhost') }}">Visit Portal</a>
        </div>
    </div>
</body>
</html>
