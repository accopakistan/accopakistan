@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="site-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="site-pagination__item site-pagination__btn is-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <x-icon name="arrow-left" style="width:1rem;height:1rem;" />
                <span class="d-none-mobile">{{ __('Previous') }}</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="site-pagination__item site-pagination__btn" aria-label="{{ __('pagination.previous') }}">
                <x-icon name="arrow-left" style="width:1rem;height:1rem;" />
                <span class="d-none-mobile">{{ __('Previous') }}</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="site-pagination__numbers">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="site-pagination__item site-pagination__ellipsis" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="site-pagination__item site-pagination__num is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="site-pagination__item site-pagination__num" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="site-pagination__item site-pagination__btn" aria-label="{{ __('pagination.next') }}">
                <span class="d-none-mobile">{{ __('Next') }}</span>
                <x-icon name="arrow-right" style="width:1rem;height:1rem;" />
            </a>
        @else
            <span class="site-pagination__item site-pagination__btn is-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <span class="d-none-mobile">{{ __('Next') }}</span>
                <x-icon name="arrow-right" style="width:1rem;height:1rem;" />
            </span>
        @endif
    </nav>
@endif
