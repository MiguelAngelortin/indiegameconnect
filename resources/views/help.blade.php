@extends('layouts.app')

@section('title', 'Help — IndieGameConnect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <h1 class="form-title mb-2">Help & User Manual</h1>
            <p class="mb-4" style="color: var(--font);">Everything you need to know to get the most out of IndieGameConnect.</p>

            {{-- 1. Getting started --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-1')">
                    <span>1. Getting Started</span>
                    <span class="help-arrow" id="arrow-help-1">▼</span>
                </button>
                <div class="help-content" id="help-1">
                    <p>Welcome to IndieGameConnect! To get started, create a free account by clicking <strong>Register</strong> in the top navigation bar.</p>
                    <p>During registration you will be asked to choose a role:</p>
                    <ul>
                        <li><strong>User</strong> — browse games, follow developers, like and comment on posts.</li>
                        <li><strong>Developer</strong> — everything a user can do, plus upload and manage your own games and publish devlog posts.</li>
                    </ul>
                    <p>Once registered you will receive a welcome email with useful links to get started.</p>
                </div>
            </div>

            {{-- 2. Exploring games and developers --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-2')">
                    <span>2. Exploring Games and Developers</span>
                    <span class="help-arrow" id="arrow-help-2">▼</span>
                </button>
                <div class="help-content" id="help-2">
                    <p>Click <strong>Games</strong> in the navbar to browse all available indie games. You can filter by:</p>
                    <ul>
                        <li><strong>Search</strong> — type any part of the game title.</li>
                        <li><strong>Genre</strong> — select a genre from the dropdown.</li>
                        <li><strong>Status</strong> — filter by Alpha, Beta, Release or Cancelled.</li>
                    </ul>
                    <p>Click on any game card to open its full page, where you can read the description, see the genres, engine, version and access the download link if available.</p>
                    <p>Click <strong>Developers</strong> in the navbar to browse all registered developers. You can search by name and see their trust level and follower count. The top 3 developers of the month appear highlighted with medals.</p>
                </div>
            </div>

            {{-- 3. Following games and developers --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-3')">
                    <span>3. Following Games and Developers</span>
                    <span class="help-arrow" id="arrow-help-3">▼</span>
                </button>
                <div class="help-content" id="help-3">
                    <p>You need to be logged in to follow games and developers.</p>
                    <ul>
                        <li>On any game page click the <strong>Follow</strong> button to follow that game. Click again to unfollow.</li>
                        <li>On any developer profile click the <strong>Follow</strong> button to follow that developer.</li>
                    </ul>
                    <p>Following games adds their devlog posts to your personal feed. Following developers lets you keep track of who you support.</p>
                    <p>You can see all your followed games and developers on your profile page.</p>
                </div>
            </div>

            {{-- 4. My Feed --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-4')">
                    <span>4. My Feed</span>
                    <span class="help-arrow" id="arrow-help-4">▼</span>
                </button>
                <div class="help-content" id="help-4">
                    <p>Your feed is a personalised timeline of devlog posts from games you follow, ordered from newest to oldest.</p>
                    <p>Click <strong>My Feed</strong> in the navbar to access it. If you are not following any games yet, start following some to populate your feed.</p>
                    <p>Each post in the feed shows the game cover, the post title, a preview of the content and the publication date. Click on a post to read it in full.</p>
                </div>
            </div>

            {{-- 5. Creating and uploading a game --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-5')">
                    <span>5. Creating and Uploading a Game</span>
                    <span class="help-arrow" id="arrow-help-5">▼</span>
                </button>
                <div class="help-content" id="help-5">
                    <p>This section is only available for <strong>Developer</strong> accounts. Click <strong>Create Game</strong> in the navbar to open the form.</p>
                    <p><strong>Required fields:</strong></p>
                    <ul>
                        <li><strong>Title</strong> — the name of your game.</li>
                        <li><strong>Description</strong> — tell players what your game is about.</li>
                        <li><strong>Genres</strong> — select one or more genres that fit your game.</li>
                        <li><strong>Status</strong> — Alpha, Beta, Release or Cancelled.</li>
                        <li><strong>Engine</strong> — the engine used to build the game.</li>
                    </ul>
                    <p><strong>Optional fields:</strong></p>
                    <ul>
                        <li><strong>Publisher / Developer</strong> — your studio or personal name.</li>
                        <li><strong>Release date</strong> — planned or actual release date.</li>
                        <li><strong>Cover image</strong> — upload an image (recommended 600×900px, 2:3 ratio).</li>
                        <li><strong>Download URL</strong> — link where players can download your game.</li>
                        <li><strong>Version</strong> — current build version.</li>
                    </ul>
                    <p>IndieGameConnect does not host game files directly. To get a download URL, upload your game to an external service first:</p>
                    <ul>
                        <li><a href="https://itch.io/upload-new" target="_blank" style="color: var(--purple);">itch.io</a> — the most popular platform for indie games.</li>
                        <li><a href="https://gamejolt.com/dashboard" target="_blank" style="color: var(--purple);">GameJolt</a> — another great option for indie developers.</li>
                    </ul>
                    <p>Both services are free and moderate content, ensuring a safe experience for players.</p>
                </div>
            </div>

            {{-- 6. Devlog posts --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-6')">
                    <span>6. Devlog Posts</span>
                    <span class="help-arrow" id="arrow-help-6">▼</span>
                </button>
                <div class="help-content" id="help-6">
                    <p>As a developer you can publish devlog posts on your game page to keep your community updated on development progress.</p>
                    <p>To create a post, go to your game page and click <strong>New Post</strong>. Fill in the title, content and optionally attach an image.</p>
                    <p>Players can:</p>
                    <ul>
                        <li><strong>Like</strong> a post by clicking the heart button.</li>
                        <li><strong>Comment</strong> on a post — you need to be logged in.</li>
                        <li><strong>Reply</strong> to a comment by clicking the Reply button under it.</li>
                    </ul>
                    <p>You can edit or delete your own posts and comments at any time using the buttons in the top right corner of each post or comment.</p>
                </div>
            </div>

            {{-- 7. Your profile --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-7')">
                    <span>7. Your Profile</span>
                    <span class="help-arrow" id="arrow-help-7">▼</span>
                </button>
                <div class="help-content" id="help-7">
                    <p>Click your name in the navbar to visit your public profile. From there you can click <strong>Edit Profile</strong> to update:</p>
                    <ul>
                        <li><strong>Name</strong> — your display name.</li>
                        <li><strong>Email</strong> — your login email.</li>
                        <li><strong>Bio</strong> — a short description about yourself.</li>
                        <li><strong>Profile image</strong> — upload a photo or avatar.</li>
                        <li><strong>Password</strong> — change your current password.</li>
                    </ul>
                    <p>Your profile image is stored securely and used only within IndieGameConnect. For more information see our <a href="/legal" style="color: var(--purple);">Privacy Policy</a>.</p>
                </div>
            </div>

            {{-- 8. Supporting developers --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-8')">
                    <span>8. Supporting Developers</span>
                    <span class="help-arrow" id="arrow-help-8">▼</span>
                </button>
                <div class="help-content" id="help-8">
                    <p>If you enjoy a developer's work you can support them directly through their profile page. Developers can link their donation pages from platforms such as:</p>
                    <ul>
                        <li><strong>Ko-fi</strong></li>
                        <li><strong>PayPal</strong></li>
                        <li><strong>Patreon</strong></li>
                        <li><strong>Other</strong> — any custom link the developer chooses.</li>
                    </ul>
                    <p>Click the donation button on their profile to be taken directly to their page on that platform. IndieGameConnect does not process any payments — all transactions happen externally.</p>
                </div>
            </div>

            {{-- 9. Supporting IndieGameConnect --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-9')">
                    <span>9. Supporting IndieGameConnect</span>
                    <span class="help-arrow" id="arrow-help-9">▼</span>
                </button>
                <div class="help-content" id="help-9">
                    <p>IndieGameConnect is an independent platform built with passion for the indie game community. If you want to support the project you can do so via:</p>
                    <ul>
                        <li><strong>GitHub</strong> — star the repository and contribute.</li>
                        <li><strong>PayPal</strong> — make a small donation to help keep the platform running.</li>
                    </ul>
                    <p>You can find the links in the welcome email you received when you registered and in the <a href="/" style="color: var(--purple);">Home</a> page.</p>
                </div>
            </div>

            {{-- 10. Contact and legal --}}
            <div class="help-section">
                <button class="help-toggle" onclick="toggleHelp('help-10')">
                    <span>10. Contact and Legal</span>
                    <span class="help-arrow" id="arrow-help-10">▼</span>
                </button>
                <div class="help-content" id="help-10">
                    <p>If you have any questions, found a bug or want to report content, use the <a href="/contact" style="color: var(--purple);">Contact</a> form. You can select the subject that best fits your enquiry:</p>
                    <ul>
                        <li><strong>Technical Support</strong> — something is not working as expected.</li>
                        <li><strong>Report Content</strong> — report inappropriate content.</li>
                        <li><strong>Suggestion</strong> — ideas to improve the platform.</li>
                        <li><strong>Other</strong> — anything else.</li>
                    </ul>
                    <p>For information about how we handle your data, cookies and terms of use, visit our <a href="/legal" style="color: var(--purple);">Legal</a> page.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleHelp(id) {
        const content = document.getElementById(id);
        const arrow = document.getElementById('arrow-' + id);
        const isOpen = content.style.display === 'block';
        content.style.display = isOpen ? 'none' : 'block';
        arrow.textContent = isOpen ? '▼' : '▲';
    }
</script>
@endpush