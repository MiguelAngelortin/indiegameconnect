@extends('layouts.app')

@section('title', 'Legal — IndieGameConnect')

@section('content')
<div class="container d-flex justify-content-center mt-5 mb-5">
    <div class="games-form col-md-8">
        <h2 class="form-title mb-1">Legal</h2>
        <p class="text-center mb-4" style="opacity: 0.7; font-size: 0.95rem;">Last updated: {{ date('Y') }}</p>

        {{-- PRIVACY POLICY --}}
        <h4 class="game-title mb-3" style="color: var(--purple);">Privacy Policy</h4>

        <p style="opacity: 0.85;">IndieGameConnect collects only the data necessary to provide the service: name, email address and role. This data is used exclusively to manage your account and is never shared with third parties.</p>

        <p style="opacity: 0.85;">When you submit a game, the information you provide (title, description, images, links) will be publicly visible on the platform. You are responsible for ensuring you have the rights to share that content.</p>

        <p style="opacity: 0.85;">We do not use tracking cookies or advertising systems. The platform may store session data to keep you logged in.</p>

        <hr style="border-color: var(--border); margin: 2rem 0;">

        {{-- TERMS OF USE --}}
        <h4 class="game-title mb-3" style="color: var(--purple);">Terms of Use</h4>

        <p style="opacity: 0.85;">By creating an account on IndieGameConnect you agree to the following terms:</p>

        <ul style="opacity: 0.85; line-height: 2;">
            <li>You will not upload content that is offensive, illegal or infringes third-party rights.</li>
            <li>You will not impersonate other users or developers.</li>
            <li>You will not attempt to manipulate the trust level or rating systems.</li>
            <li>Developers are responsible for the accuracy of the information they publish about their games.</li>
            <li>The platform reserves the right to remove content or suspend accounts that violate these terms.</li>
        </ul>

        <hr style="border-color: var(--border); margin: 2rem 0;">

        {{-- CONTACT --}}
        <p class="text-center" style="opacity: 0.6; font-size: 0.9rem;">
            If you have any questions about these terms <br>  
            <a href="/contact" style="color: var(--purple);">contact us here</a>.
        </p>
    </div>
</div>
@endsection