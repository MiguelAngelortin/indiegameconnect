@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center mt-4">
        <ul style="display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; align-items: center; flex-wrap: wrap;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--border); color: var(--font); border-radius: 6px; padding: 6px 12px; display: inline-block; opacity: 0.4; cursor: not-allowed;">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--border); color: var(--font); border-radius: 6px; padding: 6px 12px; text-decoration: none; display: inline-block;">&lsaquo;</a>
                </li>
            @endif

            {{-- Números --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span style="font-family: var(--orbitron); font-size: 0.75rem; color: var(--font); padding: 6px 4px; opacity: 0.5;">{{ $element }}</span>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--purple); color: var(--dark); border-radius: 6px; padding: 6px 12px; display: inline-block; font-weight: bold;">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--border); color: var(--font); border-radius: 6px; padding: 6px 12px; text-decoration: none; display: inline-block;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--border); color: var(--font); border-radius: 6px; padding: 6px 12px; text-decoration: none; display: inline-block;">&rsaquo;</a>
                </li>
            @else
                <li>
                    <span style="font-family: var(--orbitron); font-size: 0.75rem; background: var(--border); color: var(--font); border-radius: 6px; padding: 6px 12px; display: inline-block; opacity: 0.4; cursor: not-allowed;">&rsaquo;</span>
                </li>
            @endif

        </ul>
    </nav>
@endif