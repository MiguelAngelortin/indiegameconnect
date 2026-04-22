<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #1a1a1a;
            padding: 2rem;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 1rem;
            overflow: hidden;
            border: 2px solid #9683ec;
        }
        .header {
            background-color: #0d1117;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            font-family: 'Orbitron', sans-serif;
            color: #9683ec;
            font-size: 1.8rem;
            margin: 0;
            letter-spacing: 2px;
        }
        .header p {
            color: #c9d1d9;
            font-size: 0.85rem;
            margin: 0.5rem 0 0 0;
        }
        .body {
            padding: 2rem;
            background-color: #ffffff;
            color: #1a1a1a;
        }
        .body h2 {
            font-family: 'Orbitron', sans-serif;
            color: #0d1117;
            font-size: 1.1rem;
        }
        .body p {
            line-height: 1.6;
            color: #333333;
        }
        .features {
            background-color: #f4f0ff;
            border-left: 3px solid #9683ec;
            border-radius: 0 0.5rem 0.5rem 0;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
        }
        .features ul {
            margin: 0;
            padding: 0;
            list-style: none;
            color: #333333;
        }
        .features ul li {
            padding: 0.3rem 0;
        }
        .features ul li::before {
            content: '▶ ';
            color: #9683ec;
            font-size: 0.7rem;
        }
        .btn {
            display: inline-block;
            background-color: #9683ec;
            color: #ffffff !important;
            padding: 12px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            font-size: 0.85rem;
            letter-spacing: 1px;
            margin-top: 1rem;
        }
        .footer {
            background-color: #0d1117;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: #c9d1d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INDIEGAMECONNECT</h1>
            <p>The indie game discovery platform</p>
        </div>
        <div class="body">
            <h2>Welcome, {{ $user->name }}!</h2>
            <p>Your account has been created successfully. You are now part of a community that supports indie game developers from day one.</p>
            <div class="features">
                <ul>
                    <li>Discover and follow indie games</li>
                    <li>Follow your favourite developers</li>
                    <li>Like and comment on devlogs</li>
                    <li>Support developers directly</li>
                </ul>
            </div>
            <p>Ready to explore?</p>
            <a href="{{ url('/') }}" class="btn">START EXPLORING</a>
        </div>
        <div class="footer">
            <p>IndieGameConnect &copy; {{ date('Y') }} — You received this email because you registered on our platform.</p>
        </div>
    </div>
</body>
</html>