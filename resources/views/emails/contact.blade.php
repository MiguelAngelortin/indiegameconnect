<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
                            <h1 style="color:#9683ec; margin:0; font-size:1.5rem; letter-spacing:3px; font-family:Arial,sans-serif;">NEW CONTACT MESSAGE</h1>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="background-color:#161b22; padding:2rem; color:#c9d1d9;">

                            {{-- SUBJECT --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d1117; border-left:3px solid #9683ec; border-radius:0 0.5rem 0.5rem 0; margin-bottom:1.5rem;">
                                <tr>
                                    <td style="padding:1rem 1.5rem;">
                                        <p style="color:#9683ec; font-size:0.75rem; margin:0 0 0.25rem 0; text-transform:uppercase; letter-spacing:1px;">Subject</p>
                                        <p style="color:#c9d1d9; margin:0; font-size:1rem;"><strong>{{ $senderSubject }}</strong></p>
                                    </td>
                                </tr>
                            </table>

                            {{-- FROM --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d1117; border-left:3px solid #9683ec; border-radius:0 0.5rem 0.5rem 0; margin-bottom:1.5rem;">
                                <tr>
                                    <td style="padding:1rem 1.5rem;">
                                        <p style="color:#9683ec; font-size:0.75rem; margin:0 0 0.25rem 0; text-transform:uppercase; letter-spacing:1px;">From</p>
                                        <p style="color:#c9d1d9; margin:0; font-size:1rem;"><strong>{{ $senderName }} — {{ $senderEmail }}</strong></p>
                                    </td>
                                </tr>
                            </table>

                            {{-- MESSAGE --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d1117; border-left:3px solid #9683ec; border-radius:0 0.5rem 0.5rem 0;">
                                <tr>
                                    <td style="padding:1rem 1.5rem;">
                                        <p style="color:#9683ec; font-size:0.75rem; margin:0 0 0.25rem 0; text-transform:uppercase; letter-spacing:1px;">Message</p>
                                        <p style="color:#c9d1d9; margin:0; font-size:1rem;">{{ $senderMessage }}</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color:#0d1117; padding:1rem 2rem; text-align:center; color:#c9d1d9; font-size:0.75rem; border-top:3px solid #9683ec;">
                            <p style="margin:0;">© {{ date('Y') }} IndieGameConnect. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>