<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 2rem;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(150, 131, 236, 0.3);
        }
        .header {
            background-color: #0d1117;
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid #9683ec;
        }
        .logo {
            display: block;
            margin: 0 auto 1rem auto;
            height: 80px;
            width: auto;
        }
        .header h1 {
            color: #9683ec;
            margin: 0;
            font-size: 2rem;
            letter-spacing: 3px;
            font-family: Arial, sans-serif;
        }
        .tagline {
            color: #c9d1d9;
            font-size: 0.85rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.7;
        }
        .body {
            background-color: #161b22;
            padding: 2rem;
            color: #c9d1d9;
        }
        .body h2 {
            color: #9683ec;
            font-size: 1.2rem;
            font-family: Arial, sans-serif;
        }
        .features {
            background-color: #0d1117;
            border-left: 3px solid #9683ec;
            border-radius: 0 0.5rem 0.5rem 0;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
        }
        .features h4 {
            color: #9683ec;
            margin: 0 0 0.5rem 0;
        }
        .features ul {
            margin: 0;
            padding: 0;
            list-style: none;
            color: #c9d1d9;
        }
        .features ul li {
            padding: 0.4rem 0;
        }
        .features ul li::before {
            content: '★ ';
            color: #9683ec;
            font-size: 0.7rem;
        }
        .cta {
            text-align: center;
            padding: 0.5rem 0 1rem 0;
        }
        .cta p {
            color: #9683ec;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .btn {
            display: inline-block;
            background-color: #9683ec;
            color: #0d0d14 !important;
            padding: 14px 36px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }
        .donate {
            text-align: center;
            padding: 0.5rem 0 0 0;
            border-top: 1px solid #30363d;
            margin-top: 1rem;
        }
        .donate p {
            color: #9683ec;
            font-size: 0.8rem;
            margin-bottom: 0.75rem;
        }
        .donate-cards {
            display: table;
            width: 100%;
            margin-top: 1rem;
        }
        .donate-card {
            display: table-cell;
            width: 50%;
            padding: 0 0.5rem;
            vertical-align: top;
        }
        .donate-card a {
            display: block;
            background-color: #0d1117;
            border: 1px solid #9683ec;
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
            text-decoration: none;
        }
        .donate-card img {
            width: 80px;
            display: block;
            margin: 0 auto 0.5rem auto;
        }
        .donate-card span {
            color: #c9d1d9;
            font-size: 0.8rem;
        }
        .footer {
            background-color: #0d1117;
            padding: 1rem 2rem;
            text-align: center;
            color: #c9d1d9;
            font-size: 0.75rem;
            border-top: 3px solid #9683ec;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 2rem; background-color: #f4f4f4;">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; border-radius:1rem; overflow:hidden;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background-color:#0d1117; padding:2rem; text-align:center; border-bottom:3px solid #9683ec;">
                            <img src="https://imgur.com/biIvLNy.png" alt="Logo" style="display:block; margin:0 auto 1rem auto; height:80px; width:auto;">
                            <h1 style="color:#9683ec; margin:0; font-size:2rem; letter-spacing:3px; font-family:Arial,sans-serif;">INDIE GAME CONNECT</h1>
                            <p style="color:#c9d1d9; font-size:0.85rem; margin:0.5rem 0 0 0; opacity:0.7;">The platform where indie developers share their games and players discover them before anyone else.</p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="background-color:#161b22; padding:2rem; color:#c9d1d9;">
                            <h2 style="color:#9683ec; font-size:1.2rem; font-family:Arial,sans-serif;">Welcome, {{ $user->name }}!</h2>
                            <p>Your account has been created successfully!</p>

                            {{-- FEATURES --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d1117; border-left:3px solid #9683ec; border-radius:0 0.5rem 0.5rem 0; padding:1rem 1.5rem; margin:1.5rem 0;">
                                <tr>
                                    <td style="padding:1rem 1.5rem;">
                                        <h4 style="color:#9683ec; margin:0 0 0.5rem 0;">NOW YOU CAN ENJOY ALL OUR FEATURES:</h4>
                                        <table cellpadding="0" cellspacing="0">
                                            <tr><td style="color:#9683ec; font-size:0.7rem; padding:0.4rem 0.5rem 0.4rem 0;">★</td><td style="color:#c9d1d9; padding:0.4rem 0;">Discover indie games and developers.</td></tr>
                                            <tr><td style="color:#9683ec; font-size:0.7rem; padding:0.4rem 0.5rem 0.4rem 0;">★</td><td style="color:#c9d1d9; padding:0.4rem 0;">Follow them to find out everything.</td></tr>
                                            <tr><td style="color:#9683ec; font-size:0.7rem; padding:0.4rem 0.5rem 0.4rem 0;">★</td><td style="color:#c9d1d9; padding:0.4rem 0;">Like and comment on posts.</td></tr>
                                            <tr><td style="color:#9683ec; font-size:0.7rem; padding:0.4rem 0.5rem 0.4rem 0;">★</td><td style="color:#c9d1d9; padding:0.4rem 0;">Support developers directly.</td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:0.5rem 0 1rem 0;">
                                        <p style="color:#9683ec; font-size:1rem; margin-bottom:1rem;">Ready to explore?</p>
                                        <a href="{{ url('/') }}" style="display:inline-block; background-color:#9683ec; color:#0d0d14; padding:14px 36px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:1.1rem; letter-spacing:2px;">START EXPLORING</a>
                                    </td>
                                </tr>
                            </table>

                            {{-- DONATE --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #30363d; margin-top:1rem; padding-top:0.5rem;">
                                <tr>
                                    <td align="center" style="padding:0.5rem 0 0.75rem 0;">
                                        <p style="color:#9683ec; font-size:0.8rem; margin:0;">Support our project or check our code:</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:0 0.5rem;">
                                                    <a href="https://github.com/MiguelAngelortin/indiegameconnect.git" style="display:block; background-color:#0d1117; border:1px solid #9683ec; border-radius:0.5rem; padding:1rem; text-align:center; text-decoration:none;">
                                                        <img src="https://imgur.com/elANOR1.png" alt="GitHub" style="width:80px; display:block; margin:0 auto 0.5rem auto;">
                                                        <span style="color:#c9d1d9; font-size:0.8rem;">View on GitHub</span>
                                                    </a>
                                                </td>
                                                <td style="padding:0 0.5rem;">
                                                    <a href="https://paypal.me/IndieGameConnect" style="display:block; background-color:#0d1117; border:1px solid #9683ec; border-radius:0.5rem; padding:1rem; text-align:center; text-decoration:none;">
                                                        <img src="https://imgur.com/aqDorQK.png" alt="PayPal" style="width:80px; display:block; margin:0 auto 0.5rem auto;">
                                                        <span style="color:#c9d1d9; font-size:0.8rem;">Support us</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color:#0d1117; padding:1rem 2rem; text-align:center; color:#c9d1d9; font-size:0.75rem; border-top:3px solid #9683ec; opacity:0.7;">
                            <p style="margin:0;">© {{ date('Y') }} IndieGameConnect. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>