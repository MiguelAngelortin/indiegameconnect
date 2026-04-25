<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/email.css">
</head>
<body>
    <div class="container">
        <div class="header">
    <img class="logo" src="{{ asset('img/4.png') }}" alt="Logo">
    <h1>INDIE GAME CONNECT</h1>
    <p class="tagline">The platform where indie developers share their games and players discover them before anyone else.</p>
</div>

<div class="body">
    <h2>Welcome, {{ $user->name }}!</h2>
    <p>Your account has been created successfully!</p>
    <div class="features">
        <h4>NOW YOU CAN ENJOY ALL OUR FEATURES:</h4>
        <ul>
            <li>Discover indie games and developers.</li>
            <li>Follow them to find out everything.</li>
            <li>Like and comment on posts.</li>
            <li>Support developers directly.</li>
        </ul>
    </div>
    <div class="cta">
        <p>Ready to explore?</p>
        <a href="{{ url('/') }}" class="btn">START EXPLORING</a>
    </div>
    <div class="donate">
    <p>Support our project or check our code:</p>
    <div class="donate-cards">
        <a href="https://github.com/MiguelAngelortin/indiegameconnect.git" class="donate-card">
            <img src="/img/email/github_email.png" alt="GitHub">
            <span>View on GitHub</span>
        </a>
        <a href="https://paypal.me/IndieGameConnect" class="donate-card">
            <img src="/img/email/paypal_email.png" alt="PayPal">
            <span>Support us</span>
        </a>
    </div>
</div>
</body>
</html>