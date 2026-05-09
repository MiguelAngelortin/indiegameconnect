@extends('layouts.app')
@section('title', $post->title)
@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="post-view">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="game-title">{{ $post->title }}</h2>
                            <small>{{ $post->created_at->diffForHumans() }} — {{ $post->user->name }}</small>
                        </div>
                        @auth
                            @if(Auth::user()->id === $post->user_id)
                                <div class="d-flex gap-2">
                                    <a href="/games/{{ $game->id }}/posts/{{ $post->id }}/edit" class="game-edit-link">{{ __('games.edit_post') }}</a>
                                    <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}" class="game-delete-form" id="form-post-{{ $post->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="game-delete-link" onclick="openDeleteModal('form-post-{{ $post->id }}', 'post')">{{ __('games.delete_post') }}</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <p class="mt-3">{{ $post->content }}</p>
                    @if($post->image_url)
                        <img class="post-img mb-3" src="{{ $post->image_url }}" alt="post_img">
                    @endif
                    <button type="button" class="btn-register mt-2 mb-2"
                        @auth onclick="document.getElementById('like-form').submit()" @endauth
                        @guest onclick="document.getElementById('loginModal').classList.add('active')" @endguest>
                        {!! $userLiked ? '<span style="filter: drop-shadow(0 0 2px #000) drop-shadow(0 0 2px #000);">❤️</span>' : '🤍' !!} {{ $post->likes->count() }} {{ __('games.likes') }}
                    </button>
                    @auth
                        <form id="like-form" method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/like" style="display:none;">
                            @csrf
                        </form>
                    @endauth
                </div>

                {{-- Comments --}}
                <div class="mt-4">
                    <h5 class="game-title">{{ __('games.comments') }}</h5>
                    @auth
                        <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/comments/store" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="3" placeholder="{{ __('games.write_comment') }}" required></textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn-register">{{ __('games.comment_btn') }}</button>
                        </form>
                    @endauth

                    @forelse($comments as $comment)
                        <div class="card mb-3 comment-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <small class="ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                        <p class="mt-2">{{ $comment->content }}</p>
                                    </div>
                                    @auth
                                        @if(Auth::user()->id === $comment->user_id || Auth::user()->role === 'admin')
                                            <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/comments/{{ $comment->id }}" class="game-delete-form" id="form-comment-{{ $comment->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="game-delete-link" onclick="openDeleteModal('form-comment-{{ $comment->id }}', 'comment')">{{ __('games.delete_btn') }}</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                @auth
                                    <button class="btn btn-sm btn-outline-secondary mb-2" onclick="toggleReply('reply-{{ $comment->id }}')">{{ __('games.reply_btn') }}</button>
                                    <div id="reply-{{ $comment->id }}" style="display:none;" class="mt-2 mb-2">
                                        <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/comments/store">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="mb-2">
                                                <textarea name="content" class="form-control" rows="2" placeholder="{{ __('games.write_reply') }}" required></textarea>
                                            </div>
                                            <button type="submit" class="btn-register">{{ __('games.reply_btn') }}</button>
                                        </form>
                                    </div>
                                @endauth

                                {{-- Replies --}}
                                @foreach($comment->replies as $reply)
                                    <div class="card mt-2 reply-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>{{ $reply->user->name }}</strong>
                                                    <small class="ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                                    <p class="mt-2">{{ $reply->content }}</p>
                                                </div>
                                                @auth
                                                    @if(Auth::user()->id === $reply->user_id || Auth::user()->role === 'admin')
                                                        <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/comments/{{ $reply->id }}" class="game-delete-form" id="form-reply-{{ $reply->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="game-delete-link" onclick="openDeleteModal('form-reply-{{ $reply->id }}', 'reply')">{{ __('games.delete_btn') }}</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p>{{ __('games.no_comments') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal confirmación borrado --}}
    <div id="deleteModal" class="modal-overlay">
        <div class="games-form text-center" style="position:relative;">
            <button onclick="closeDeleteModal()" class="modal-close">&times;</button>
            <h5 class="game-title mb-3" id="deleteModalTitle">{{ __('games.are_you_sure') }}</h5>
            <p id="deleteModalText">{{ __('games.cannot_undo') }}</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button onclick="closeDeleteModal()" class="btn-register" style="background: var(--border); color: var(--font) !important;">{{ __('games.cancel') }}</button>
                <button onclick="confirmDelete()" class="btn-register" style="background: #c62828;">{{ __('games.delete_btn') }}</button>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    let formToSubmit = null;

    const messages = {
        post: '{{ __('games.delete_post_confirm') }}',
        comment: '{{ __('games.delete_comment_confirm') }}',
        reply: '{{ __('games.delete_reply_confirm') }}',
    };

    function openDeleteModal(formId, type) {
        formToSubmit = document.getElementById(formId);
        document.getElementById('deleteModalText').textContent = messages[type];
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
        formToSubmit = null;
    }

    function confirmDelete() {
        if (formToSubmit) formToSubmit.submit();
    }

    function toggleReply(id) {
        const div = document.getElementById(id);
        div.style.display = div.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endpush
@endsection